<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Correo Electrónico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            color: #333;
            text-align: center;
        }
        
        p {
            color: #666;
        }
        
        .button {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
    <h1>Nuevo comentario en tu artículo</h1>

    <p>Hola {{ $comment->article->user->name }},</p>

    <p>¡Te informamos que alguien ha comentado en tu artículo "{{ $comment->article->title }}"!</p>

    <p><strong>Comentario:</strong> {{ $comment->content }}</p>

    <p>Puedes acceder al artículo y revisar los comentarios haciendo clic en el siguiente botón:</p>

    <img src="{{ $message->embed($comment->article->image)}}" alt="Imagen">

    {{-- @component('mail::button', ['url' => route('articles.show', $comment->article)])
        Ver Artículo
    @endcomponent --}}

    <p>¡Gracias por utilizar nuestro sitio!</p>

    <p>Saludos,<br>{{ config('app.name') }}</p>
    </div>
</body>
</html>
