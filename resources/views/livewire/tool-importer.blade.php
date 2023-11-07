<div class="card">
    <div class="card-header">
        Import tool
    </div>

    <div class="card-body">
        <div class="mb-4">
            <div class="border border-danger p-4 mb-4 rounded">
                <h4>Generate Prompt:</h4>
                <div class="form-group mb-4">
                    <label class="fw-bold">Home Page URL:</label>
                    <input type="url" class="form-control" wire:model="url" />
                    <button class="btn btn-primary mt-2" wire:click="getData()" wire:target="getData">
                        <span>Generate Prompt 🔥</span>
                        <div wire:loading='getData' class="spinner-border spinner-border-sm text-white" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>
                </div>
            </div>
            @php
                // assuming we will get 1000 tokens from output
                $totalAssitentTokens = 1000;
                $sytemTokens = estimateTokenUsage($promptForSystem);
                $userTokens = empty($contentForPrompt) ? 0 : estimateTokenUsage($contentForPrompt);
                $totalTokensRequired = $sytemTokens + $userTokens + $totalAssitentTokens;
                //output is 6 cent per 1000 token
                $totalCents = ($sytemTokens + $userTokens) * 0.00003 + $totalAssitentTokens * 0.00006;
                $totalRupees = $totalCents * 84;
            @endphp
            {{-- todo: show loader --}}
            {{-- todo: show estimated tokens and price and option to send request to openAI --}}
            <div class="border border-info p-4 mb-4 rounded"
                style="display: {{ empty($contentForPrompt) ? 'none' : 'block' }}">


                <div class="row">
                    <div class="col-8">
                        <label for="">Prompt</label>
                        <textarea class="form-control" id="prompt" cols="100" rows="10">
{{ @$promptForSystem }}

Content of the website is as following:
{{ @$contentForPrompt }}
</textarea>
                    </div>
                    <div class="col-4 w-full">
                        <label for="">Extracted content</label>
                        <textarea class="form-control" id="" cols="30" rows="10">{{ $contentForPrompt }}</textarea>
                    </div>
                </div>




                <div class="mb-2  mt-4">
                    <div class="mb-2">
                        <span class="mr-2">
                            System toknes: <strong>{{ $sytemTokens }}</strong>
                        </span>
                        <span class="mr-2">
                            User toknes:
                            @if (@$userTokens > 500)
                                <strong class="text-success">{{ @$userTokens }}</strong>
                            @else
                                <strong class="text-danger">{{ @$userTokens }}</strong>
                            @endif
                        </span>
                        <span>
                            Assistant toknes: <strong>2000</strong>
                        </span>
                        <span>
                            Total: <strong>{{ $totalTokensRequired }}</strong>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between gap-4">
                        <div>
                            <button class="btn btn-success text-white" wire:click="getResponseFromOpenAi()">
                                <span>💲 Get JSON From OpenAi 🔥</span>
                                <div wire:loading wire:target="getResponseFromOpenAi"
                                    class="spinner-border spinner-border-sm text-white" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </button>
                            <span>
                                {{-- <strong>{{ round($totalCents, 2) }}</strong>$ --}}
                                <strong>{{ round($totalRupees, 2) }}</strong>₹
                            </span>
                        </div>
                        <button class="btn btn-success float-right" id="copy-button">
                            Copy prompt
                        </button>
                    </div>
                </div>
            </div>
        </div>


        @if (!empty($toolSocialHandlesDTO))
            <div class="mb-4 border border-success p-4 rounded">
                <h4>Submit JSON</h4>
                <form method="POST" action="{{ route('admin.tools.import') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="toolSocialHandlesDTO" value="{{ json_encode($toolSocialHandlesDTO) }}">

                    <div class="form-group mb-4">
                        <label class="fw-bold">Website Home Page</label>
                        <input type="url" value="{{ $url }}" class="form-control" name="home_page_url"
                            required />
                    </div>

                    {{-- social handles --}}
                    <div class="border p-2 my-4 rounded">
                        <p class="fw-bold text-success text-lg">Social media</p>
                        @if (!empty($toolSocialHandlesDTO['instagramUserId']))
                            <p>
                                <span>Instragram:</span>
                                <a target="_blank"
                                    href="https://www.instagram.com/{{ $toolSocialHandlesDTO['instagramUserId'] }}">
                                    {{ $toolSocialHandlesDTO['instagramUserId'] }}
                                </a>
                            </p>
                        @endif
                        @if (!empty($toolSocialHandlesDTO['twitterUserId']))
                            <p>
                                <span>Twitter:</span>
                                <a target="_blank"
                                    href="https://twitter.com/{{ $toolSocialHandlesDTO['twitterUserId'] }}">
                                    {{ $toolSocialHandlesDTO['twitterUserId'] }}
                                </a>
                            </p>
                        @endif
                        @if (!empty($toolSocialHandlesDTO['facebookUserId']))
                            <p>
                                <span>Facebook:</span>
                                <a target="_blank"
                                    href="https://www.instagram.com/{{ $toolSocialHandlesDTO['facebookUserId'] }}">
                                    {{ $toolSocialHandlesDTO['facebookUserId'] }}
                                </a>
                            </p>
                        @endif
                        @if (!empty($toolSocialHandlesDTO['tiktokUserId']))
                            <p>
                                <span>TikTok:</span>
                                <a target="_blank"
                                    href="https://www.tiktok.com/{{ '@' }}{{ $toolSocialHandlesDTO['tiktokUserId'] }}">
                                    {{ $toolSocialHandlesDTO['tiktokUserId'] }}
                                </a>
                            </p>
                        @endif
                        @if (!empty($toolSocialHandlesDTO['youtubeChannelId']))
                            <p>
                                <span>Youtube Channel:</span>
                                <a target="_blank"
                                    href="https://www.youtube.com/channel/{{ $toolSocialHandlesDTO['youtubeChannelId'] }}">
                                    {{ $toolSocialHandlesDTO['youtubeChannelId'] }}
                                </a>
                            </p>
                        @endif
                        @if (!empty($toolSocialHandlesDTO['linkedinCompanyId']))
                            <p>
                                <span>Linkedin Company:</span>
                                <a target="_blank"
                                    href="https://www.linkedin.com/company/{{ $toolSocialHandlesDTO['linkedinCompanyId'] }}">
                                    {{ $toolSocialHandlesDTO['linkedinCompanyId'] }}
                                </a>
                            </p>
                        @endif
                        @if (!empty($toolSocialHandlesDTO['emailId']))
                            <p>
                                <span>Email:</span>
                                <a target="_blank" href="mailto:{{ $toolSocialHandlesDTO['emailId'] }}">
                                    {{ $toolSocialHandlesDTO['emailId'] }}
                                </a>
                            </p>
                        @endif
                        @if (!empty($toolSocialHandlesDTO['androidApId']))
                            <p>
                                <span>Android App:</span>
                                <a target="_blank"
                                    href="https://play.google.com/store/apps/details?id={{ $toolSocialHandlesDTO['androidApId'] }}">
                                    {{ $toolSocialHandlesDTO['androidApId'] }}
                                </a>
                            </p>
                        @endif
                        @if (!empty($toolSocialHandlesDTO['IOSAppID']))
                            <p>
                                <span>IOS App:</span>
                                <a target="_blank"
                                    href="https://apps.apple.com/app/{{ $toolSocialHandlesDTO['IOSAppID'] }}">
                                    {{ $toolSocialHandlesDTO['IOSAppID'] }}
                                </a>
                            </p>
                        @endif
                        @if (!empty($toolSocialHandlesDTO['telegramChannelId']))
                            <p>
                                <span>telegramChannelId:</span>
                                <a target="_blank"
                                    href="https://t.me/{{ $toolSocialHandlesDTO['telegramChannelId'] }}">
                                    {{ $toolSocialHandlesDTO['telegramChannelId'] }}
                                </a>
                            </p>
                        @endif
                        @if (!empty($toolSocialHandlesDTO['discordChannelInviteId']))
                            <p>
                                <span>discordChannelInviteId:</span>
                                <a target="_blank"
                                    href="https://discord.com/invite/{{ $toolSocialHandlesDTO['discordChannelInviteId'] }}">
                                    {{ $toolSocialHandlesDTO['discordChannelInviteId'] }}
                                </a>
                            </p>
                        @endif
                        @if (!empty($toolSocialHandlesDTO['subredditId']))
                            <p>
                                <span>subredditId:</span>
                                <a target="_blank"
                                    href="https://www.reddit.com/r/{{ $toolSocialHandlesDTO['subredditId'] }}">
                                    {{ $toolSocialHandlesDTO['subredditId'] }}
                                </a>
                            </p>
                        @endif
                        @if (!empty($toolSocialHandlesDTO['githubRepositoryPath']))
                            <p>
                                <span>githubRepositoryPath:</span>
                                <a target="_blank"
                                    href="https://github.com//{{ $toolSocialHandlesDTO['githubRepositoryPath'] }}">
                                    {{ $toolSocialHandlesDTO['githubRepositoryPath'] }}
                                </a>
                            </p>
                        @endif
                        @if (!empty($toolSocialHandlesDTO['chromeExtensionId']))
                            <p>
                                <span>chromeExtensionId:</span>
                                <a target="_blank"
                                    href="https://chrome.google.com/webstore/detail/{{ $toolSocialHandlesDTO['chromeExtensionId'] }}">
                                    {{ $toolSocialHandlesDTO['chromeExtensionId'] }}
                                </a>
                            </p>
                        @endif
                        @if (!empty($toolSocialHandlesDTO['firefoxExtensionId']))
                            <p>
                                <span>firefoxExtensionId:</span>
                                <a target="_blank"
                                    href="https://addons.mozilla.org/en-US/firefox/addon/{{ $toolSocialHandlesDTO['firefoxExtensionId'] }}">
                                    {{ $toolSocialHandlesDTO['firefoxExtensionId'] }}
                                </a>
                            </p>
                        @endif
                    </div>

                    <div class="form-group mb-4">
                        <label class="fw-bold">tool_json_string</label>
                        @if ($jsonParseStatus == 1)
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <span>Json parsed successfully</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @elseif ($jsonParseStatus == -1)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <span>Failed to Parse this JSON</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        <textarea type="text" class="form-control" rows="10" name="tool_json_string" required>{{ @$responseJson }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary float-right my-3">
                        Import
                    </button>
                </form>
            </div>
        @endif

    </div>



    <script>
        // Find the textarea and copy button elements
        var textarea = document.getElementById("prompt");
        var copyButton = document.getElementById("copy-button");

        // Add a click event listener to the copy button
        copyButton.addEventListener("click", function() {
            // Select the text in the textarea
            textarea.select();

            try {
                // Copy the selected text to the clipboard
                document.execCommand("copy");
                copyButton.innerHTML = 'Copied';
                // alert("Text copied to clipboard");
            } catch (err) {
                console.error("Unable to copy text to clipboard");
            }
        });
    </script>

</div>
