<?php

namespace App\Core;

class Shortcode
{
    public static function parse(string $content): string
    {
        return preg_replace_callback('/\[([a-zA-Z0-9_-]+)(.*?)\]/', function ($matches) {
            $tag = strtolower(trim($matches[1]));
            $rawAttributes = trim($matches[2] ?? '');
            $attributes = self::parseAttributes($rawAttributes);

            return self::render($tag, $attributes);
        }, $content);
    }

    private static function parseAttributes(string $text): array
    {
        $attributes = [];

        preg_match_all('/([a-zA-Z0-9_-]+)="([^"]*)"/', $text, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $attributes[$match[1]] = $match[2];
        }

        return $attributes;
    }

    private static function render(string $tag, array $attributes): string
    {
        switch ($tag) {
            case 'form':
                return self::renderForm($attributes);

            case 'products':
                return self::renderProducts($attributes);

            case 'booking':
                return self::renderBooking($attributes);

            case 'gallery':
                return self::renderGallery($attributes);

            case 'testimonial':
                return self::renderTestimonial($attributes);

            default:
                return '<span class="badge text-bg-warning">Shortcode inconnu : [' . htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') . ']</span>';
        }
    }

    private static function renderForm(array $attributes): string
    {
        $id = htmlspecialchars($attributes['id'] ?? 'default', ENT_QUOTES, 'UTF-8');

        return '
            <div class="alert alert-info mt-3">
                <strong>Shortcode formulaire</strong><br>
                Formulaire ID : ' . $id . '
            </div>
        ';
    }

    private static function renderProducts(array $attributes): string
    {
        $category = htmlspecialchars($attributes['category'] ?? 'toutes', ENT_QUOTES, 'UTF-8');

        return '
            <div class="alert alert-secondary mt-3">
                <strong>Shortcode produits</strong><br>
                Catégorie : ' . $category . '
            </div>
        ';
    }

    private static function renderBooking(array $attributes): string
    {
        $id = htmlspecialchars($attributes['id'] ?? 'default', ENT_QUOTES, 'UTF-8');

        return '
            <div class="alert alert-success mt-3">
                <strong>Shortcode réservation</strong><br>
                Booking ID : ' . $id . '
            </div>
        ';
    }

    private static function renderGallery(array $attributes): string
    {
        $id = htmlspecialchars($attributes['id'] ?? 'default', ENT_QUOTES, 'UTF-8');

        return '
            <div class="alert alert-dark mt-3">
                <strong>Shortcode galerie</strong><br>
                Galerie ID : ' . $id . '
            </div>
        ';
    }

    private static function renderTestimonial(array $attributes): string
    {
        $limit = htmlspecialchars($attributes['limit'] ?? '3', ENT_QUOTES, 'UTF-8');

        return '
            <div class="alert alert-warning mt-3">
                <strong>Shortcode avis</strong><br>
                Limite : ' . $limit . '
            </div>
        ';
    }
}
