<?php
session_start();
if(isset($_SESSION['login'])){
  header('Location: dashboard');
}else{
  header('Location: auth/login');
}