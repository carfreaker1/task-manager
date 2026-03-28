<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class ChatBotController extends Controller
{
    private $apiKey;
    private $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
    }

    public function handleChat(Request $request)
    {
        if (!$this->apiKey) {
            return response()->json(['reply' => 'Error: Gemini API key is missing.']);
        }

        $userMessage = $request->input('message');
        
        // Define the structured tools that the LLM can call
        $tools = [
            [
                "functionDeclarations" => [
                    [
                        "name" => "get_tasks",
                        "description" => "Get a list of all tasks. You can use this to see what tasks are available, or count how many tasks there are.",
                        "parameters" => [
                            "type" => "OBJECT",
                            "properties" => (object)[
                                "limit" => [
                                    "type" => "INTEGER",
                                    "description" => "Number of tasks to return (optional)"
                                ]
                            ]
                        ]
                    ],
                    [
                        "name" => "get_projects",
                        "description" => "Get a list of all projects and their IDs. Can use this to count projects.",
                        "parameters" => [
                            "type" => "OBJECT",
                            "properties" => (object)[]
                        ]
                    ],
                    [
                        "name" => "get_users",
                        "description" => "Get a list of registered users and employees. Can be used to find who uses the system.",
                        "parameters" => [
                            "type" => "OBJECT",
                            "properties" => (object)[]
                        ]
                    ]
                ]
            ]
        ];

        // Format the message history into the shape required by Gemini API
        $contents = [
            [
                "role" => "user",
                "parts" => [
                    ["text" => $userMessage]
                ]
            ]
        ];

        $payload = [
            "systemInstruction" => [
                "parts" => [
                    [
                        "text" => "You are a helpful, professional AI assistant built directly into a Task Management application. You have access to real-time database queries through tools. When asked about tasks, projects, or users, ALWAYS use the function calling tools to get accurate real-time data before answering. Be concise, polite, and use markdown formatting to make your answers easy to read (use standard markdown, avoid html blocks). Never make up data."
                    ]
                ]
            ],
            "contents" => $contents,
            "tools" => $tools
        ];

        // First request to check if Gemini wants to call a function
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($this->apiUrl . '?key=' . $this->apiKey, $payload);

        if ($response->failed()) {
            return response()->json(['reply' => 'API Error: ' . $response->body()]);
        }

        $responseData = $response->json();
        
        $functionCall = null;
        $initialReplyText = "";
        
        // Loop through all parts to find text and function calls
        if (isset($responseData['candidates'][0]['content']['parts'])) {
            foreach ($responseData['candidates'][0]['content']['parts'] as $part) {
                if (isset($part['functionCall'])) {
                    $functionCall = $part['functionCall'];
                }
                if (isset($part['text'])) {
                    $initialReplyText .= $part['text'];
                }
            }
        }

        // If the API blocked it for some reason (e.g. safety settings)
        if (isset($responseData['candidates'][0]['finishReason']) && $responseData['candidates'][0]['finishReason'] !== 'STOP') {
            return response()->json(['reply' => 'I cannot answer that. Reason: ' . $responseData['candidates'][0]['finishReason']]);
        }

        // If no function call, just return whatever text we grabbed
        if (!$functionCall) {
            $reply = trim($initialReplyText) !== '' ? $initialReplyText : 'Debug - Response received but no text or function: ' . json_encode($responseData);
            return response()->json(['reply' => $reply]);
        }

        // If we have a function call, execute it
        $functionName = $functionCall['name'];
        $args = $functionCall['args'] ?? [];

        // Execute the local PHP model logic
        $functionResult = $this->executeFunction($functionName, $args);

        // Give the result back to Gemini so it can generate the final natural language answer
        
        // Grab the AI's first reply to append to our history
        $modelContent = $responseData['candidates'][0]['content'];
        
        // PHP json_encode turns empty objects into empty arrays "[]". 
        // Gemini strictly requires "args" to be an object "{}". We must cast it if it's empty.
        if (isset($modelContent['parts'])) {
            // Because arrays are passed by value in PHP, we iterate with reference '&' to modify it
            foreach ($modelContent['parts'] as &$part) {
                if (isset($part['functionCall']) && isset($part['functionCall']['args']) && empty($part['functionCall']['args'])) {
                    $part['functionCall']['args'] = (object)[];
                }
            }
        }

        // Append the repaired model's first reply
        $payload['contents'][] = $modelContent;

        // Append the function response
        $payload['contents'][] = [
            "role" => "user", 
            "parts" => [
                [
                    "functionResponse" => [
                        "name" => $functionName,
                        "response" => [
                            "name" => $functionName,
                            "content" => $functionResult
                        ]
                    ]
                ]
            ]
        ];

        // Make the second request with the DB data
        $finalResponse = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($this->apiUrl . '?key=' . $this->apiKey, $payload);

        if ($finalResponse->successful()) {
            $finalData = $finalResponse->json();
            $replyText = "";
            if (isset($finalData['candidates'][0]['content']['parts'])) {
                foreach ($finalData['candidates'][0]['content']['parts'] as $part) {
                    if (isset($part['text'])) {
                        $replyText .= $part['text'];
                    }
                }
            }
            if (empty(trim($replyText))) {
                $replyText = "Debug - Second pass returned: " . json_encode($finalData);
            }
            return response()->json(['reply' => $replyText]);
        }
        
        return response()->json(['reply' => 'Failed communicating back to AI on second pass. API says: ' . $finalResponse->body()]);
    }

    private function executeFunction($name, $args)
    {
        try {
            switch ($name) {
                case 'get_tasks':
                    $limit = $args['limit'] ?? 50;
                    return Task::limit($limit)->get()->toArray();
                case 'get_projects':
                    return Project::all()->toArray();
                case 'get_users':
                    // Prevent returning passwords or full tokens, just return names and emails.
                    return User::select('id', 'name', 'email')->get()->toArray();
                default:
                    return ["error" => "Function '$name' not found."];
            }
        } catch (\Exception $e) {
            return ["error" => "Database error executing '$name'."];
        }
    }
}
