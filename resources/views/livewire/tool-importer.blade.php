<div class="card">
    <div class="card-header">
        Import tool
    </div>

    <div class="card-body">
        <div class="d-relative mb-4">
            <h4>Step1: Copy prompt</h4>
            <button class="btn btn-success" id="copy-button">Copy</button>
            <textarea class="form-control" name="" id="prompt" cols="100" rows="4">{{ $promptForSystem }}</textarea>
        </div>

        <hr>

        <div class="mb-4">
            <h4>Step 2: Get content</h4>
            <div class="form-group mb-4">
                <label class="fw-bold">Website Home Page</label>
                <input type="url" class="form-control" wire:model="url" />
                <button class="btn btn-primary" wire:click='getData()'>GetData</button>
            </div>
            @if (!empty($contentForPrompt))
                <textarea class="form-control" name="" id="prompt" cols="100" rows="20">{{ $contentForPrompt }}</textarea>
            @endif
        </div>
        <hr>

        <div class="mb-4">
            <h4>Step 3: submit json</h4>
            <form method="POST" action="{{ route('admin.tools.import') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-4">
                    <label class="fw-bold">Website Home Page</label>
                    <input type="url" class="form-control" name="home_page_url" required />
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">tool_json_string</label>
                    <textarea type="text" class="form-control" rows="10" name="tool_json_string" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary float-right my-3">
                    Import
                </button>
            </form>
        </div>

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
