import './bootstrap';
import SwaggerUI from 'swagger-ui'
import 'swagger-ui/dist/swagger-ui.css'

SwaggerUI({
    url: '/api.yaml',
    dom_id: '#swagger-api',
});
