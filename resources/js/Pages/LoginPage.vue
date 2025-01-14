<template>
  <div class="login-container">
    <img src="@/../img/logo.png" alt="Logo" class="logo" />
    <div class="login-form">
      <h2>Se connecter</h2>
      <form @submit.prevent="submit">
        <div class="form-group">
          <label for="username">Nom d'utilisateur</label>
          <input 
            type="text" 
            id="username" 
            v-model="form.username"
            placeholder="Nom d'utilisateur" 
          />
          <div v-if="form.errors.username" class="error-message">
            {{ form.errors.username }}
          </div>
        </div>
        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input 
            type="password" 
            id="password" 
            v-model="form.password"
            placeholder="Mot de passe" 
          />
        </div>
        <button type="submit" class="login-button" :disabled="form.processing">
          Se connecter
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  username: '',
  password: ''
});

const submit = () => {
  form.post('/login');
};
</script>

<style scoped>
.login-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start; 
  height: 100vh;
  background-color: #f9f9f9;
  padding-top: 50px;
}
  
  .logo {
    width: 600px;
    margin-bottom: 20px;
  }
  
  .login-form {
  background-color: #fff;
  padding: 30px;
  border-radius: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  width: 400px;
  text-align: center;
  margin-top: 40px; 
}
  
  .form-group {
    margin-bottom: 30px;
    text-align: left;
  }
  
  label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
  }
  
  input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 10px;
  }
  
  .login-button {
    width: 100%;
    padding: 10px;
    background-color: #72C489;
    color: white;
    border: none;
    border-radius: 15px;
    cursor: pointer;
    font-size: 16px;
  }
  
  .login-button:hover {
    background-color: #45a049;
  }

  .error-message {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.25rem;
  }

  .login-button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
  }
  </style>