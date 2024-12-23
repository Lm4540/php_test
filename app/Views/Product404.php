<!DOCTYPE html>
<html lang="es">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Producto no encontrado</title>
      <link rel="apple-touch-icon" href="/img/apple-icon.png">
      <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
      <link rel="stylesheet" href="/css/fontawesome.min.css">
      <style>
            body,
            html {
                  height: 100%;
                  margin: 0;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  background-color: #f8f9fa;
            }

            .error-container {
                  text-align: center;
                  max-width: 600px;
                  margin: auto;
            }

            .error-title {
                  font-size: 100px;
                  font-weight: bold;
                  color: #000040;
            }

            .error-message {
                  font-size: 24px;
                  margin-bottom: 20px;
            }

            .home-button {
                  margin-top: 20px;
            }
      </style>
</head>

<body>
      <div class="error-container">
            <h1 class="error-title">404</h1>
            <p class="error-message">Producto no encontrado</p>
            <p>Lo sentimos, pero el producto que estás buscando no existe o ha sido removido.</p>
            <button onclick="history.back()" class="btn btn-primary home-button"><i class="fas fa-arrow-left"></i>
                  &nbsp;&nbsp;Volver</a>
      </div>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>