<?php
/*
Démarre la session PHP si elle n'est pas déjà active.
La session permet de stocker des informations utilisateur (ex: admin connecté)
et de les conserver entre plusieurs pages.
*/
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}