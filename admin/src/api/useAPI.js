import axios from 'axios'
import middleware401 from './middleware401'
import middlewareCSRF from './middlewareCSRF'

/**
 * Initialize Axios instance to call the API
 * @param {string} endpoint either 'web' or 'api' (default)
 * @returns {AxiosInstance}
 */
export const useApi = (endpoint = 'api') => {
	const { API_HOST, API_PATH } = import.meta.env

	let baseURL
	
	if (endpoint === 'api') {
		//baseURL = API_HOST + API_PATH || 'http://localhost/trade-tools/public/api'
	    baseURL = API_HOST + API_PATH || 'http://trade-tools.simpleitrunner.ru:3000/api'
        console.log(baseURL);
	} else if (endpoint === 'web') {
		baseURL = API_HOST || 'http://localhost:8000'
	}

	const axiosInstance = axios.create({
		baseURL,
		headers: { 'X-Requested-With': 'XMLHttpRequest' },
		withCredentials: true,
	})

	axiosInstance.interceptors.request.use( middlewareCSRF, err => Promise.reject(err)) 

	axiosInstance.interceptors.response.use(resp => resp, middleware401)

	return axiosInstance
}