<!DOCTYPE html>
<html>
<head>
    <title>Rich Text Editor Example</title>

    <!-- Load Trix CSS from jsDelivr CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.0.3/dist/trix.css">

    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        h2 { margin-bottom: 20px; }
        button { margin-top: 10px; padding: 8px 20px; background: blue; color: white; border: none; cursor: pointer; }
        button:hover { background: darkblue; }
    </style>
</head>
<body>

    <h2>Rich Text Editor Example</h2>

    @if(session('success'))
        <div style="background: green; color: white; padding: 5px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('richtext.store') }}">
        @csrf

        <!-- Hidden input for Trix -->
        <input id="bio" type="hidden" name="bio">
        <trix-editor input="bio"></trix-editor>

        <br>
        <button type="submit">Save Content</button>
    </form>

    <!-- Load Trix JS from jsDelivr CDN -->
    <script src="https://cdn.jsdelivr.net/npm/trix@2.0.3/dist/trix.umd.min.js"></script>

</body>
</html>