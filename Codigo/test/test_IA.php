<?php
// Tu clave API de Hugging Face
// $apiKey = "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";  

// La URL del modelo que deseas usar
$model = "distilgpt2";  // Usando un modelo más pequeño, puedes probar con "gpt2" o "gpt-neo"
$url = "https://api-inference.huggingface.co/models/$model";

// El texto de entrada para la IA
$inputText = "Genera un título claro y conciso para una tarea de software";

// Datos a enviar en el POST (el texto a procesar)
$data = json_encode([
    "inputs" => $inputText,
    "parameters" => [
        "max_length" => 20,  // Limita la longitud de la respuesta a 20 tokens
        "temperature" => 0.3,  // Reduce la creatividad para respuestas más controladas
        "top_p" => 0.8,  // Reduce la diversidad en las respuestas
        "no_repeat_ngram_size" => 3,  // Evita la repetición de n-gramas
    ]
]);

// Configuración de cURL
$options = [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json",
    ],
    CURLOPT_POSTFIELDS => $data,
];

// Inicializar y ejecutar la solicitud
$ch = curl_init();
curl_setopt_array($ch, $options);
$response = curl_exec($ch);

// Verificar si hubo un error en la ejecución de cURL
if ($response === false) {
    echo "Error en cURL: " . curl_error($ch);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Mostrar la respuesta cruda para diagnóstico
echo "<pre>Respuesta cruda de la IA: " . htmlspecialchars($response) . "</pre>";

// Intentar procesar la respuesta
$result = json_decode($response, true);

// Verificar si la respuesta contiene los datos esperados
if (isset($result[0]['generated_text'])) {
    echo "Título de la tarea: " . $result[0]['generated_text'];
} else {
    // Si no se encuentra la clave, mostrar más detalles para depuración
    echo "Hubo un problema con la respuesta de la IA. Respuesta obtenida: <pre>" . print_r($result, true) . "</pre>";
}
?>
