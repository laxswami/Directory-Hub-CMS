import axios from 'axios'

export default {
    methods: {
        /**
         * Return the CSRF token on the page.
         *
         * @returns {string}
         */
        getToken() {
            return document.head.querySelector('meta[name="csrf-token"]').content
        },

        /**
         * Create a base request.
         *
         * @returns {AxiosInstance}
         */
        request() {
            let instance = axios.create()

            instance.defaults.headers.common['X-CSRF-TOKEN'] = this.getToken()
            instance.defaults.baseURL = '/' + Canvas.path

            const requestHandler = request => {
                // Add any request modifiers...
                return request
            }

            const errorHandler = error => {
                // Add any error modifiers...
                switch (error.response.status) {
                    case 405:
                    case 401:
                        this.logout()
                        break
                    default:
                        break
                }

                return Promise.reject({...error})
            }

            const successHandler = response => {
                // Add any response modifiers...
                return response
            }

            instance.interceptors.request.use(request =>
                requestHandler(request)
            )

            instance.interceptors.response.use(
                response => successHandler(response),
                error => errorHandler(error)
            )

            return instance
        },

        /**
         * Log out of the application.
         *
         * @returns void
         */
        logout() {
            let instance = axios.create()

            instance.defaults.headers.common['X-CSRF-TOKEN'] = this.getToken()
            instance.defaults.baseURL = '/'

            instance.post('/logout').then(response => {
                window.location.href = '/login'
            })
        }
    },
}
