<style>
    .loader {
        width: 20px;
        height: 20px;
        border: 2.5px solid rgba(0, 0, 0, 0.15);
        border-top-color: currentColor;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
<span {{ $attributes->merge(['class' => 'inline-block w-5 h-5 border-2 border-t-2 border-t-current border-gray-300 rounded-full animate-spin loader']) }}></span>