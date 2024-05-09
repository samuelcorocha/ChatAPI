<x-app-layout>
    <div id="swagger-ui"></div>
    <script>
        window.onload = () => {
            SwaggerUIBundle({
                url: '../documentacoes/doc.json',
                dom_id: '#swagger-ui',
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
            });
            $("#swagger-ui").appendTo("#swagger-page");
        };
    </script>
</x-app-layout>
