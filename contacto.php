<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Miniaturas y Colecciones</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <style>
        /* Estilos personalizados */
        #container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        #content {
            flex-grow: 1;
            width: 100%;
            max-width: 1200px;
            margin-bottom: 100px;
        }

        .buscador {
            margin-bottom: 20px;
        }

        form {
            display: flex;
            align-items: center;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .preguntas-frecuentes {
            text-align: center;
        }

        h2, h3 {

            margin-top: 10px;
            margin-bottom: 10px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        .contacto-info {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 50px;
        }

        .buttonContacto {
           display: flex;
           justify-content: center;
           align-items: center;
           width: 60px;
           height: 60px;
           border-radius: 100%;
           border: none;
           background-color: #30C04F;
         }

          .buttonContacto:hover {
          background-color: #2bac47;
       }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div id="container">
        <div id="content">

            <!-- Sección de preguntas frecuentes -->
            <div class="preguntas-frecuentes container">
                <h2>ENVÍO Y ENTREGA</h2>
                <ul>
                    <li>El envío se realiza entre 3-4 días laborables</li>
                    <li>La entrega será entre 6-7 días laborables una vez que se realiza el envío</li>
                </ul>

                <br>

                <h3>CONTACTA CON NOSOTROS</h3>
                <p>Cerraremos el 25 de diciembre y el 1 de enero.</p>

                <div class="contacto-info">
                <button class="buttonContacto">
                  <svg xmlns="http://www.w3.org/2000/svg" width="32" viewBox="0 0 32 32" height="32" fill="none" class="svg-icon"><path stroke-width="2" 
                  stroke-linecap="round" stroke="#fff" fill-rule="evenodd" 
                  d="m24.8868 19.1288c-1.0274-.1308-2.036-.3815-3.0052-.7467-.7878-.29-1.6724-.1034-2.276.48-.797.8075-2.0493.9936-2.9664.3258-1.4484-1.055-2.7233-2.3295-3.7783-3.7776-.6681-.9168-.4819-2.1691.3255-2.9659.5728-.6019.7584-1.4748.4802-2.2577-.3987-.98875-.6792-2.02109-.8358-3.07557-.2043-1.03534-1.1138-1.7807-2.1694-1.77778h-3.18289c-.60654-.00074-1.18614.25037-1.60035.69334-.40152.44503-.59539 1.03943-.53345 1.63555.344 3.31056 1.47164 6.49166 3.28961 9.27986 1.64878 2.5904 3.84608 4.7872 6.43688 6.4356 2.7927 1.797 5.9636 2.9227 9.2644 3.289h.1778c.5409.0036 1.0626-.2 1.4581-.569.444-.406.6957-.9806.6935-1.5822v-3.1821c.0429-1.0763-.7171-2.0185-1.7782-2.2046z" clip-rule="evenodd"></path></svg>
                 </button>
                    <div>
                        <h4>PRODUCTOS Y PEDIDOS</h4>
                        <p>+34 95555555</p>
                        <p>10:00-18:00 (Lunes - Viernes)</p>
                        <p>9:00-18:00 (Sábado)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
