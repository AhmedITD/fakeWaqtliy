BackEnd
    cd back && php artisan serve
    'allowed_origins' => ['http://localhost:5174', 'http://127.0.0.1:5174', 'http://localhost:5178', 'http://127.0.0.1:5178'], for the vue port so the vue must run in one of the selected ports to allow the acsses to the backend

FrontEnd
    cd front && npm run dev
    VITE_API_BASE_URL=http://localhost:8000/api/v1 for the bakend port
    VITE_IFRAME_URL="http://localhost:5174/" for the Ifram parent origin




