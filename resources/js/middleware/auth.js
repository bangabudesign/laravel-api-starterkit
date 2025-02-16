export default function authMiddleware(to, from, next) {
    const token = localStorage.getItem('_token');
    
    // Check if the route requires authentication
    if (to.matched.some(record => record.meta.requiresAuth)) {
      if (!token) {
        // If no token, redirect to login page
        next({ name: 'Home' });
      } else {
        // Token exists, proceed to the route
        next();
      }
    } else if (to.matched.some(record => record.meta.requiresGuest)) {
      if (token) {
        // Token exists, proceed to the route
        next({ name: 'Dashboard' });
      } else {
        // If no token, redirect to login page
        next();
      }
    } else {
      // No authentication required, proceed
      next();
    }
}