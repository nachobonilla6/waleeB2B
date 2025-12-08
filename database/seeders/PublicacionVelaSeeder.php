<?php

namespace Database\Seeders;

use App\Models\PublicacionVela;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublicacionVelaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publicaciones = [
            [
                'texto' => 'Hoy compartimos con ustedes nuestra nueva colección de velas artesanales hechas a mano con cera de soja natural. Cada vela está diseñada con amor y dedicación para crear ambientes acogedores y relajantes en tu hogar.',
                'hashtags' => '#velas #artesanal #hogar #decoracion #relajacion',
                'fecha_publicacion' => now()->subDays(5),
            ],
            [
                'texto' => 'Las velas aromáticas son perfectas para crear un ambiente tranquilo después de un día largo. Nuestras fragancias incluyen lavanda, vainilla y canela que te ayudarán a relajarte y disfrutar de momentos especiales en casa.',
                'hashtags' => '#aromaterapia #relajacion #bienestar #hogar #velas',
                'fecha_publicacion' => now()->subDays(4),
            ],
            [
                'texto' => 'Celebramos el lanzamiento de nuestra línea ecológica de velas hechas con materiales completamente sostenibles. Cada compra ayuda a proteger el medio ambiente mientras disfrutas de la calidez y belleza de nuestras creaciones.',
                'hashtags' => '#ecologico #sostenible #medioambiente #velas #naturaleza',
                'fecha_publicacion' => now()->subDays(3),
            ],
            [
                'texto' => 'Las velas son el complemento perfecto para cualquier ocasión especial. Ya sea una cena romántica, una celebración familiar o simplemente un momento de paz, nuestras velas iluminarán tus mejores momentos con estilo y elegancia.',
                'hashtags' => '#ocasiones #romantico #celebración #elegancia #iluminacion',
                'fecha_publicacion' => now()->subDays(2),
            ],
            [
                'texto' => 'Descubre la magia de nuestras velas de colores vibrantes que se adaptan a cualquier decoración. Disponibles en múltiples tamaños y formas, encontrarás la vela perfecta para cada rincón de tu hogar y crear espacios únicos.',
                'hashtags' => '#colores #decoracion #hogar #magia #diseño',
                'fecha_publicacion' => now()->subDays(1),
            ],
            [
                'texto' => 'Nuestras velas de larga duración están diseñadas para brindarte horas de iluminación suave y constante. Hechas con los mejores materiales y técnicas artesanales, garantizamos calidad y satisfacción en cada producto que ofrecemos.',
                'hashtags' => '#calidad #duracion #artesanal #satisfaccion #productos',
                'fecha_publicacion' => now(),
            ],
            [
                'texto' => 'La luz de una vela puede transformar completamente el ambiente de cualquier espacio. Nuestras creaciones están pensadas para aquellos que buscan agregar un toque especial a sus momentos más importantes y crear recuerdos inolvidables.',
                'hashtags' => '#transformacion #momentos #recuerdos #especial #luz',
                'fecha_publicacion' => now()->addDays(1),
            ],
            [
                'texto' => 'Regala luz y calidez con nuestras velas personalizadas. Perfectas para cumpleaños, aniversarios o cualquier ocasión especial. Cada vela puede ser personalizada con mensajes y diseños únicos que harán tu regalo aún más especial.',
                'hashtags' => '#regalos #personalizado #especial #cumpleaños #aniversario',
                'fecha_publicacion' => now()->addDays(2),
            ],
            [
                'texto' => 'Las velas no solo iluminan, también crean atmósferas mágicas que inspiran y relajan. Nuestra colección incluye fragancias exclusivas que despertarán tus sentidos y te transportarán a lugares tranquilos y serenos.',
                'hashtags' => '#magia #atmosfera #fragancias #sentidos #tranquilidad',
                'fecha_publicacion' => now()->addDays(3),
            ],
            [
                'texto' => 'Únete a nuestra comunidad de amantes de las velas y descubre consejos, trucos y nuevas tendencias en decoración. Comparte tus experiencias y forma parte de una familia que valora la belleza y el arte de la iluminación natural.',
                'hashtags' => '#comunidad #tendencias #decoracion #consejos #familia',
                'fecha_publicacion' => now()->addDays(4),
            ],
            [
                'texto' => 'Cada vela cuenta una historia única. Desde el momento en que la encendemos hasta que se consume, crea momentos especiales que quedan grabados en nuestra memoria. Nuestras velas están hechas para ser parte de tus mejores recuerdos.',
                'hashtags' => '#historias #memoria #recuerdos #momentos #especial',
                'fecha_publicacion' => now()->addDays(5),
            ],
            [
                'texto' => 'La calidad se nota en cada detalle. Nuestras velas están elaboradas con los más altos estándares de excelencia, utilizando solo los mejores ingredientes y técnicas tradicionales que garantizan un producto excepcional para nuestros clientes.',
                'hashtags' => '#calidad #excelencia #tradicional #clientes #productos',
                'fecha_publicacion' => now()->addDays(6),
            ],
        ];

        foreach ($publicaciones as $publicacion) {
            PublicacionVela::create($publicacion);
        }
    }
}
