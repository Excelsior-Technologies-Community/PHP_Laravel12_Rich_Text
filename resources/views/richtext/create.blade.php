<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Premium Rich Text CMS</title>

    <!-- Trix CSS -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/trix@2.0.3/dist/trix.css">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg,#0f172a,#1e293b);
            min-height:100vh;
            padding:40px;
            color:white;
            transition:0.3s;
        }

        body.light{
            background:#f4f4f4;
            color:#111;
        }

        .container{
            max-width:1200px;
            margin:auto;
        }

        .topbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:30px;
        }

        .title{
            font-size:34px;
            font-weight:bold;
        }

        .toggle-btn{
            padding:10px 18px;
            border:none;
            border-radius:8px;
            background:#2563eb;
            color:white;
            cursor:pointer;
            font-weight:bold;
        }

        .stats{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
            gap:20px;
            margin-bottom:30px;
        }

        .card{
            background:rgba(255,255,255,0.1);
            padding:25px;
            border-radius:16px;
            backdrop-filter: blur(10px);
        }

        body.light .card{
            background:white;
            box-shadow:0 5px 15px rgba(0,0,0,0.1);
        }

        .card h3{
            margin-bottom:10px;
            font-size:18px;
        }

        .card p{
            font-size:28px;
            font-weight:bold;
        }

        .editor-box{
            background:rgba(255,255,255,0.1);
            padding:30px;
            border-radius:16px;
            margin-bottom:30px;
        }

        body.light .editor-box{
            background:white;
        }

        trix-editor{
            background:white;
            min-height:250px;
            border-radius:10px;
            padding:15px;
            color:black;
        }

        .btn{
            margin-top:20px;
            padding:12px 25px;
            border:none;
            border-radius:10px;
            background:#2563eb;
            color:white;
            cursor:pointer;
            font-size:16px;
            font-weight:bold;
        }

        .btn:hover{
            background:#1d4ed8;
        }

        .success{
            background:#16a34a;
            padding:14px;
            border-radius:10px;
            margin-bottom:20px;
        }

        .search{
            width:100%;
            padding:14px;
            border:none;
            border-radius:10px;
            margin-bottom:25px;
            font-size:16px;
        }

        .content-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
            gap:25px;
        }

        .content-card{
            background:rgba(255,255,255,0.1);
            padding:20px;
            border-radius:16px;
            backdrop-filter: blur(10px);
            transition:0.3s;
            overflow:hidden;
        }

        .content-card:hover{
            transform:translateY(-5px);
        }

        body.light .content-card{
            background:white;
            box-shadow:0 5px 15px rgba(0,0,0,0.1);
        }

        .content{
            max-height:200px;
            overflow:auto;
            margin-bottom:20px;
        }

        .actions{
            display:flex;
            gap:10px;
        }

        .edit-btn{
            text-decoration:none;
            background:#f59e0b;
            color:white;
            padding:10px 16px;
            border-radius:8px;
            font-weight:bold;
        }

        .delete-btn{
            background:#dc2626;
            color:white;
            border:none;
            padding:10px 16px;
            border-radius:8px;
            cursor:pointer;
            font-weight:bold;
        }

        .empty{
            text-align:center;
            padding:40px;
            opacity:0.7;
        }

        @media(max-width:768px){

            body{
                padding:20px;
            }

            .topbar{
                flex-direction:column;
                gap:20px;
            }

            .title{
                text-align:center;
                font-size:28px;
            }
        }
    </style>
</head>

<body id="body">

<div class="container">

    <div class="topbar">

        <div class="title">
            Premium Rich Text CMS
        </div>

        <button class="toggle-btn" onclick="toggleMode()">
            Toggle Mode
        </button>

    </div>

    @if(session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

    @endif

    <!-- Statistics -->

    <div class="stats">

        <div class="card">
            <h3>Total Articles</h3>
            <p>{{ $contents->count() }}</p>
        </div>

        <div class="card">
            <h3>Today's Posts</h3>
            <p>
                {{ $contents->where('created_at', '>=', now()->startOfDay())->count() }}
            </p>
        </div>

        <div class="card">
            <h3>Last Updated</h3>

            <p style="font-size:16px">

                @if($contents->count())

                    {{ $contents->first()->updated_at->diffForHumans() }}

                @else

                    No Data

                @endif

            </p>
        </div>

    </div>

    <!-- Editor -->

    <div class="editor-box">

        <h2 style="margin-bottom:20px;">
            Create New Content
        </h2>

        <form method="POST"
            action="{{ route('richtext.store') }}">

            @csrf

            <input id="bio"
                type="hidden"
                name="bio">

            <trix-editor input="bio"></trix-editor>

            @error('bio')

                <p style="color:red; margin-top:10px;">
                    {{ $message }}
                </p>

            @enderror

            <button type="submit" class="btn">
                Save Content
            </button>

        </form>

    </div>

    <!-- Search -->

    <input type="text"
        id="search"
        class="search"
        placeholder="Search saved content...">

    <!-- Content Grid -->

    <div class="content-grid" id="contentGrid">

        @forelse($contents as $item)

            <div class="content-card">

                <div class="content">

                    {!! $item->content !!}

                </div>

                <div class="actions">

                    <a href="{{ route('richtext.edit', $item->id) }}"
                        class="edit-btn">

                        Edit

                    </a>

                    <form method="POST"
                        action="{{ route('richtext.delete', $item->id) }}">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="delete-btn"
                            onclick="return confirm('Delete this content?')">

                            Delete

                        </button>

                    </form>

                </div>

            </div>

        @empty

            <div class="empty">
                No Content Found
            </div>

        @endforelse

    </div>

</div>

<!-- Trix JS -->

<script src="https://cdn.jsdelivr.net/npm/trix@2.0.3/dist/trix.umd.min.js"></script>

<script>

    // =========================
    // LIVE SEARCH
    // =========================

    document.getElementById('search')
    .addEventListener('keyup', function () {

        let value = this.value.toLowerCase();

        let cards = document.querySelectorAll('.content-card');

        cards.forEach(card => {

            card.style.display =
                card.innerText.toLowerCase().includes(value)
                ? 'block'
                : 'none';

        });

    });

    // =========================
    // DARK MODE WITH STORAGE
    // =========================

    const body = document.getElementById('body');

    // Load saved mode

    if(localStorage.getItem('theme') === 'light'){

        body.classList.add('light');

    }

    // Toggle Mode

    function toggleMode(){

        body.classList.toggle('light');

        // Save theme

        if(body.classList.contains('light')){

            localStorage.setItem('theme', 'light');

        }else{

            localStorage.setItem('theme', 'dark');

        }

    }

</script>

</body>
</html>