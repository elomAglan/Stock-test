import axios from "axios";



const API_URL = "http://127.0.0.1:8000/api";



const api = axios.create({

  baseURL: API_URL,

  headers: { "Content-Type": "application/json" },

});



// Ajoute automatiquement le token

api.interceptors.request.use((config) => {

  const token = localStorage.getItem("token");

  if (token) {

    config.headers.Authorization = `Bearer ${token}`;

  }

  return config;

});



// =======================

// Auth

// =======================

export const registerUser = (data) => api.post("/register", data);

export const loginUser = (data) => api.post("/login", data);

export const logoutUser = () => api.post("/logout");

export const getUser = () => api.get("/user"); // ✅ fonctionne après avoir ajouté la route



// =======================

// Categories

// =======================

export const getCategories = () => api.get("/categories");

export const getCategory = (id) => api.get(`/categories/${id}`);

export const createCategory = (data) => api.post("/categories", data);

export const updateCategory = (id, data) => api.put(`/categories/${id}`, data);

export const deleteCategory = (id) => api.delete(`/categories/${id}`);



// =======================

// Articles

// =======================

export const getArticles = () => api.get("/articles");

export const getArticle = (id) => api.get(`/articles/${id}`);

export const createArticle = (data) => api.post("/articles", data);

export const updateArticle = (id, data) => api.put(`/articles/${id}`, data);

export const deleteArticle = (id) => api.delete(`/articles/${id}`);



export default api;