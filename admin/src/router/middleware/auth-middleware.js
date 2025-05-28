import { useAuthStore } from '../../stores/auth.js'

export default (to, from, next) => {
	const auth = useAuthStore()

	let exceptionalRoutes = ['login', 'register', 'forgot-password', 'cryptocurrency-table-indicator']
	let isGoingExceptionalRoutes = exceptionalRoutes.includes(to.name)

	/**
	 * IF THE USER IS NOT LOGGED IN
	 */
	if (!auth.isLoggedIn) {
		console.log(to.name);
		if (isGoingExceptionalRoutes) {
			next() // The user is not logged in but it's going to exceptional routes ? fine
			return
		} else {
			next({ name: 'login' })
			return
		} // other routes than exceptional paths => /login
	}

	/**
	 * IF THE USER IS LOGGED IN
	 */
	if (auth.isLoggedIn && isGoingExceptionalRoutes) {
		next({ name: 'home', query: { 'redirect-reason': 'already logged' } })
	} else {
		next()
	}
}