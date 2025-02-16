export const auth = {
    isAuthenticated() {
      return localStorage.getItem('_token') !== null;
    },
    
    getToken() {
      return localStorage.getItem('_token');
    },
    
    setToken(token) {
      localStorage.setItem('_token', token);
    },
    
    clearToken() {
      localStorage.removeItem('_token');
    }
};