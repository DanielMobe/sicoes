<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="{{asset('resources/js/require.js')}}"></script>

        <title>TESI WS REST</title>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }
            .container{
                text-align: center;
            }
            .full-height {
                height: 100vh;
            }
            .header-home{
                width: 50%;
                margin-left: auto;
                margin-right: auto;
                margin-bottom: 50px;
            }
            .img-header{
                width: 100%;
                height: 180px;
            }
            .KB-container{
                text-align: center;
            }
            .opt-button-left{
                border: 1px solid #68b21d;
                background: #68b21d;
                color: #fff;
                font-weight: bold;
                font-size: 16px;
                padding: 10px;
                float: right;
                cursor: pointer;
                width: 50%;
            }
            .opt-button-right{
                border: 1px solid #68b21d;
                background: #68b21d;
                color: #fff;
                font-weight: bold;
                font-size: 16px;
                padding: 10px;
                float: left;
                cursor: pointer;
                width: 50%;
            }
            .KB-BUTTON{
                background: #68b21d;
                color: #fff;
                margin-top: 10px;
                font-weight: bold;
            }
            .loader{
                position: fixed;
                z-index: 1;
                background: #ccc;
                opacity: 0.5;
                width: 100%;
                height: 100%;
            }
            .loaderimg{

                height: 100px;

            }
            .loaderdiv{
                margin-right: auto;
                margin-left: auto;
                text-align: center;
                margin-top: 30%;
            }
            @media (max-width: 768px) {
                .img-header{   
                    height: auto;
                }
                .header-home{
                    width: 100%;
                }
                .opt-button-left{
                    width: 100%;
                }
                .opt-button-right{
                    width: 100%;
                    margin-top: 20px;
                }

            }
        </style>

    </head>
    <div style="display:none;" id="loader" class="loader">
        <div class="loaderdiv">
            <img class="loaderimg" src="{{asset('https://controlescolar.tesi.org.mx/storage/app/public/Images/loader.gif')}}">
        </div>
    </div>
    <div class="container">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="header-home">
                    <img class="img-header" src="{{asset('https://controlescolar.tesi.org.mx/storage/app/public/Images/Boleta/R.jpg')}}">
                </div>            
            </div>
             <div class="KB-container col-xs-12 col-sm-12 col-md-12">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <span id="genera_kardex" class="opt-button-left">Genera Kardex</span>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <span id="genera_boleta" class="opt-button-right">Genera Boleta</span>
                </div>
            </div>
            <div style="display: none;" id="kardex-pid-div" class="col-xs-12 col-sm-12 col-md-12">
                <form id="kardex">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <label for="kardex-pid">Ingresa tu Matricula:</label>
                        <br>
                        <input required maxlength="10" minlength="9" type="text" name="kardex-pid" id="kardex-pid">
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <button class="KB-BUTTON" type="submit" id="solicita-kardex">Generar Kardex</button>
                    </div>
                </form>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <button style="background: #ccc;" class="KB-BUTTON atras">Volver Atras</button>
                </div>
            </div>
            <div style="display: none;" id="boleta-pid-div" class="col-xs-12 col-sm-12 col-md-12">
                <form id="boleta">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <label for="boleta-pid">Ingresa tu Matricula:</label>
                        <br>
                        <input required maxlength="10" minlength="9" type="text" name="boleta-pid" id="boleta-pid">
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <button class="KB-BUTTON" type="submit" id="solicita-boleta">Generar Boleta</button>
                    </div>
                </form>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <button style="background: #ccc;" class="KB-BUTTON atras">Volver Atras</button>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>