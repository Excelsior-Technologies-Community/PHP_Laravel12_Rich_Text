<!DOCTYPE html>
<html>

<head>

    <title>Edit Content</title>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/trix@2.0.3/dist/trix.css">

    <style>

        body{
            font-family:Arial;
            background:#0f172a;
            padding:40px;
            color:white;
        }

        .container{
            max-width:900px;
            margin:auto;
            background:#1e293b;
            padding:30px;
            border-radius:16px;
        }

        trix-editor{
            background:white;
            color:black;
            border-radius:10px;
            min-height:250px;
        }

        .btn{
            margin-top:20px;
            padding:12px 25px;
            border:none;
            border-radius:10px;
            background:#2563eb;
            color:white;
            font-weight:bold;
            cursor:pointer;
        }

        .back{
            display:inline-block;
            margin-bottom:20px;
            color:#60a5fa;
            text-decoration:none;
        }

    </style>

</head>

<body>

<div class="container">

    <a href="/richtext" class="back">
        ← Back
    </a>

    <h2 style="margin-bottom:20px;">
        Edit Content
    </h2>

    <form method="POST"
        action="{{ route('richtext.update', $content->id) }}">

        @csrf
        @method('PUT')

        <input id="bio"
            type="hidden"
            name="bio"
            value="{{ $content->content }}">

        <trix-editor input="bio"></trix-editor>

        <button class="btn">
            Update Content
        </button>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/trix@2.0.3/dist/trix.umd.min.js"></script>

</body>
</html>