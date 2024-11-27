<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string {
        return view('landing');
    }

    public function about(): string {
        return view('about');
    }
    public function contact(): string {
        return view('contact');
    }
    public function login(): string {
        return view('login');
    }
    public function login_post() {
        //obtener los datos del cliente
        
        //verificar los datos del cliente

        //crear la cokie de session

        //retornar la redireccion hacia los catalogos       
        return redirect()->route('catalogs');
    }

    public function logout(): string {
        //destruir la session 

        //redireccionar a la pagina principal
    }

    public function register(): string {
        return view('register');
    }

    public function register_post(): string {
        // procesar los datos del cliente

        //enviar la respuesta Json 
    }
}
