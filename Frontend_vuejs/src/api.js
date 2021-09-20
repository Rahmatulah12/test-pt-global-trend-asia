import axios from 'axios'

const $axios = axios.create({
    baseURL: 'http://localhost:8089/api/',
    headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
        'Content-Type': 'application/json'
    }
});

$axios.defaults.headers.post['Access-Control-Allow-Origin'] = '*';
$axios.defaults.timeout = 7000;

export default $axios
