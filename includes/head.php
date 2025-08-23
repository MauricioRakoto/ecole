<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecole</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="http://<?= $host ?>/app-ecole/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://<?= $host ?>/app-ecole/assets/css/style.css" rel="stylesheet">
    <style>
        button.icone img {
            width: 30px; 
            height: 30px; 
            border-radius: 50%;
        }

        .photo img {
            width: 60px; 
            height: 60px; 
            border-radius: 50%;
        }
    </style>

<style>
        table th:nth-child(1),
        table td:nth-child(1) {
            width: 50px;
        }

        table th:nth-child(2),
        table td:nth-child(2) {
            width: 1%;
        }

        table th:nth-child(3),
        table td:nth-child(3) {
            width: 35%;
        }

        .links {
            display: flex;
            gap: 10px;
        }

        a.btn-data {
            width: 90px;
            height: 30px;
            background: #d3cad9;
            transition: 1s ease-in-out;
            color: black;
            font-size: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        a.btn-data:hover {
            background: #009cff;
        }

        button.icone {
            border: 0;
         }

        button.icone img {
            width: 30px; 
            height: 30px; 
            border-radius: 50%;
        }

        .photo img {
            width: 60px; 
            height: 60px; 
            border-radius: 50%;
        }

        .items {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }

        .items .card {
            width: 200px;
            height: 80px;
            background: #d3cad9;
            padding: 10px;
            border-radius: 5px;
            transition: .5s ease-in-out;
            margin-bottom: 10px;
            z-index: 5;
            border: 0;
        }

        button.select {
            width: 20px;
            height: 20px;
            border: 1px solid #000;
            border-radius: 3px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        button.select.active {
            background-color: #007bff;
        }

    </style>
</head>