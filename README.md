# PHP_Laravel12_Rich_Text

## Project Description
A Laravel 12 web application with a WYSIWYG rich text editor (Trix) to create, edit, and save formatted text content in MySQL. Ideal for blogs, bios, or CMS projects.



## Features
- Rich Text Editing: Bold, italics, headings, lists, links, etc.
- Save Content to Database
- Form Validation
- Clean Frontend
- MVC Architecture
- Success Notification




## Technology Stack
- Laravel 12 (PHP 8.2)
- Blade Templates, HTML, CSS, JavaScript
- Trix Rich Text Editor (via CDN)
- MySQL
- Composer package: tonysm/rich-text-laravel



## Project Flow
1. User opens `/richtext`
2. Types and formats content using Trix
3. Submits form → Controller validates & saves
4. Success message displayed


## Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL
- Web Browser


---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Rich_Text "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Rich_Text

```

#### Explanation:

This command installs a fresh Laravel 12 application and creates the project folder.

The cd command moves into the newly created project directory.




## STEP 2: Database Setup 

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Rich_Text
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_Rich_Text

```

### Then Run:

```
php artisan migrate

```


#### Explanation:

This step connects Laravel with the MySQL database.

The migration command creates the default Laravel tables in the database.




## STEP 3: Install Required Packages

### Install the package:

```
composer require tonysm/rich-text-laravel

```

#### Explanation

Installs the tonysm/rich-text-laravel package to handle rich text content easily.







## STEP 4: Create Migration + Model

### We will create a simple table for storing rich text content.

```
php artisan make:model RichText -m

```

### Open migration file: database/migrations/xxxx_xx_xx_create_rich_texts_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rich_texts', function (Blueprint $table) {
            $table->id();
            $table->longText('content'); // store rich text
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rich_texts');
    }
};

```


### Then Run:

```
php artisan migrate

```

#### Explanation: 

Generates a RichText model and migration to store rich text content in the database.






## STEP 5: Edit Model

### app/Models/RichText.php:

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RichText extends Model
{
    use HasFactory;

    protected $fillable = ['content'];
}

```

#### Explanation: 

Makes the content field fillable so it can be saved via mass assignment.





## STEP 6: Create Controller

### Run: 

```
php artisan make:controller RichTextController

```

### Edit app/Http/Controllers/RichTextController.php:

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RichText;

class RichTextController extends Controller
{
    public function create()
    {
        return view('richtext.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bio' => 'required',
        ]);

        RichText::create([
            'content' => $request->bio,
        ]);

        return redirect()->back()->with('success', 'Content saved!');
    }
}

```

#### Explanation: 

Handles showing the editor form (create) and saving the content (store).






## STEP 7: Create Blade View

### Create file: resources/views/richtext/create.blade.php

```
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

```

#### Explanation: 

Provides a frontend form with Trix rich text editor and a submit button.






## STEP 8: Add a Route

### Open: routes/web.php

```
use App\Http\Controllers\RichTextController;

Route::get('/richtext', [RichTextController::class, 'create']);
Route::post('/richtext', [RichTextController::class, 'store'])->name('richtext.store');

```

#### Explanation: 

Defines GET and POST routes for displaying and saving the rich text form.





## STEP 9:  Run Project

### Start server

```
php artisan serve

```

### Open in browser

```
http://127.0.0.1:8000/richtext

```

#### Explanation: 

Starts the Laravel server and opens the editor page in the browser.





## Expected Output


<img width="1919" height="836" alt="Screenshot 2026-03-09 191159" src="https://github.com/user-attachments/assets/a1be5501-8fea-4a14-8392-b616804b7b5e" />

<img width="1919" height="734" alt="Screenshot 2026-03-09 191209" src="https://github.com/user-attachments/assets/7dc69b90-cb6e-44ea-8808-311fd9d48efb" />


---


# Project Folder Structure:

```
PHP_Laravel12_Rich_Text/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── RichTextController.php      <-- Your controller
│   │   └── Kernel.php
│   ├── Models/
│   │   └── RichText.php                   <-- Your model
│   └── ...
├── bootstrap/
│   └── app.php
├── config/
│   └── app.php
├── database/
│   ├── migrations/
│   │   └── xxxx_xx_xx_create_rich_texts_table.php   <-- Migration file for rich_texts table
│   └── seeders/
├── public/
│   └── index.php
├── resources/
│   ├── views/
│   │   └── richtext/
│   │       └── create.blade.php            <-- Your blade view with Trix editor
│   └── ...
├── routes/
│   └── web.php                              <-- Your web routes
├── storage/
├── tests/
├── vendor/
├── composer.json
├── package.json
└── artisan

```
