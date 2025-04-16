<?php
    // extract the message that we will be sending to the NYU GenAI API
    $message = $_POST['message'];

    // define the project ID - this will not change
    $project_id = 334;    

    // define your NetID - this allows NYU to track usage on a user by user basis
    $net_id = 'YOUR_NET_ID_GOES_HERE';

    // this is the model you plan on using. NYU GenAI supports OpenAI's ChatGPT gpt-4o model
    $genai_resource_name = 'gpt-4o';

    // Paste your NYU GenAI API Auth Key here
    // You can obtain this key by doing the following:
    // 1. Make sure you are on campus, or connected via the NYU VPN
    // 2. Visit https://projects.rit.nyu.edu/
    // 3. Sign in
    // 4. Click the 'Get / Refresh Your GenAI API Auth Key' button
    // 5. Copy the key that appears
    // 6. Note that this key is only valid for 24 hours, so you will need to regenerate it on a daily basis
    $nyu_genai_api_auth_key = 'YOUR_API_GOES_HERE';

    // This is the API endpoint that we will use to communicate with the NYU GenAI API
    $endpoint = "https://genai-prod-nyu-rit-ai-apicast-production.apps.cloud.rt.nyu.edu/chat?gateway=default";

    // Next, we have to construct a "CURL" request
    // CURL stands for "Client URL" and is used to make network calls to resources on other servers.
    // You can think of this like PHP's way of making a fetch request

    // This is the POST data that we plan on sending to the server
    $post_data = [
        "messages" => [
            [
                "role" => "user",
                "content" => $message
            ]
        ]
    ];

    // Next, define the headers for our request to tell Google Gemini that we plan on sending JSON data
    $headers = [
        "AUTHORIZATION_KEY: $nyu_genai_api_auth_key",
        "rit_access: $project_id|$net_id|$genai_resource_name",
        "rit_timeout: 60",
        "Content-Type: application/json"
    ];

    // Finally, make the request to the API and wait for a response
    // Note that this is a synchronously operation, and PHP will "block" while a CURL request
    // The program will pause to wait for a response from Google Gemini before continuing, unlike JavaScript which handles these
    // kinds of requests asynchronously
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute cURL request
    $response = curl_exec($ch);
    
    // Close the cURL handle
    curl_close($ch);
    
    // Decode results into JSON - this will allow us to work with the result as though it was an array
    $results = json_decode($response, true);

    // Finally, extract the model's response from the $results array
    // This array has lots of data in it, and most of it isn't really necessary for our purposes
    // If you print out the full response you would see something like the following - note that this response
    // came back from the model based on the prompt "Please tell me a knock knock joke"
    //print_r($results);
    /*
        Array
        (
            [choices] => Array
                (
                    [0] => Array
                        (
                            [content_filter_results] => Array
                                (
                                    [hate] => Array
                                        (
                                            [filtered] => 
                                            [severity] => safe
                                        )

                                    [self_harm] => Array
                                        (
                                            [filtered] => 
                                            [severity] => safe
                                        )

                                    [sexual] => Array
                                        (
                                            [filtered] => 
                                            [severity] => safe
                                        )

                                    [violence] => Array
                                        (
                                            [filtered] => 
                                            [severity] => safe
                                        )

                                )

                            [finish_reason] => stop
                            [index] => 0
                            [message] => Array
                                (
                                    [content] => Sure, here you go:

        Knock, knock.

        Who's there?

        Lettuce.

        Lettuce who?

        Lettuce in, it's freezing out here!
                                    [refusal] => 
                                    [role] => assistant
                                )

                        )

                )

            [created] => 1744819588
            [id] => ID_OF_RESULT
            [model] => gpt-4o-2024-05-13
            [object] => chat.completion
            [prompt_filter_results] => Array
                (
                    [0] => Array
                        (
                            [prompt_index] => 0
                            [content_filter_results] => Array
                                (
                                    [hate] => Array
                                        (
                                            [filtered] => 
                                            [severity] => safe
                                        )

                                    [self_harm] => Array
                                        (
                                            [filtered] => 
                                            [severity] => safe
                                        )

                                    [sexual] => Array
                                        (
                                            [filtered] => 
                                            [severity] => safe
                                        )

                                    [violence] => Array
                                        (
                                            [filtered] => 
                                            [severity] => safe
                                        )

                                )

                        )

                )

            [system_fingerprint] => SYSTEM_FINGERPRINT
            [usage] => Array
                (
                    [completion_tokens] => 34
                    [completion_tokens_details] => Array
                        (
                            [accepted_prediction_tokens] => 0
                            [audio_tokens] => 0
                            [reasoning_tokens] => 0
                            [rejected_prediction_tokens] => 0
                        )

                    [prompt_tokens] => 14
                    [prompt_tokens_details] => Array
                        (
                            [audio_tokens] => 0
                            [cached_tokens] => 0
                        )

                    [total_tokens] => 48
                )

            [rit_usage] => Array
                (
                    [current_query_execution_time_sec] => 1.28
                    [current_query_cost] => 0.00058
                    [total_cost_per_user] => 0.00017
                    [limit_cost_per_user] => 10
                    [total_cost_per_resource] => 0.00017
                    [limit_cost_per_resource] => 100
                )

        )
    */

    // We only really need the model's text response, so we can extract that into a new variable
    $response_text = $results['choices'][0]['message']['content'];

    // Send the response text to the client and end the program
    print $response_text;

?>