<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<style>

h1{
    color:gray;
    text-align: center;
    font-size: 40px;
}

div.content-h2 h2{
color: red;

}

</style>

<h1>SmsPro</h1>

<h2>Alerte SMS</h2>
<p>Les données sont :</p>
<ul>
    @foreach ($filteredData as $item)
        <li>Q: {{ $item['q'] }}, N: {{ $item['n'] }}, N10: {{ $item['d']['n10'] ?? 'N/A' }}</li>
    @endforeach
</ul>

    
</body>
</html>