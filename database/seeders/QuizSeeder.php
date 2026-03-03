<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Answer;
use App\Models\JerseyOption;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        Question::query()->delete();
        JerseyOption::query()->delete();

        $questions = [
            [
                'question' => 'Quel athlète du FAC, né le 31 janvier, détient le record club Minime 80m ?',
                'coach_hint' => 'Ce minime a réalisé son record à Grenoble en juin 2025',
                'answers' => [
                    ['reponse' => 'Jules Philibert-Brun', 'is_correct' => false],
                    ['reponse' => 'Tom Chaussende', 'is_correct' => false],
                    ['reponse' => 'Adam Defour', 'is_correct' => true],
                    ['reponse' => 'Lazare Defour', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quelle athlète du FAC, née le 22 janvier, est double détentrice de Record Club Cadette ?',
                'coach_hint' => 'Elle court le 800m et le 2000m steeple',
                'answers' => [
                    ['reponse' => 'Justine Garcia', 'is_correct' => false],
                    ['reponse' => 'Evie Lebrun', 'is_correct' => true],
                    ['reponse' => 'Marine Drapier', 'is_correct' => false],
                    ['reponse' => 'Anouk Legat', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quel athlète du FAC détient le record Club Minime au javelot ?',
                'coach_hint' => 'Il a lancé à plus de 58 mètres à Saint-Étienne en juillet 2025',
                'answers' => [
                    ['reponse' => 'Timeo Klein', 'is_correct' => true],
                    ['reponse' => 'Maxence Fournet-Fayard', 'is_correct' => false],
                    ['reponse' => 'Thimothe Segouin', 'is_correct' => false],
                    ['reponse' => 'Erwan Porte', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quel athlète du FAC, né le 11 mars, détient le record toutes catégories au 400m haies ?',
                'coach_hint' => 'C\'est un des frères Dutrevis, spécialiste des haies',
                'answers' => [
                    ['reponse' => 'Erwan Dutrevis', 'is_correct' => false],
                    ['reponse' => 'Aymeric Dutrevis', 'is_correct' => true],
                    ['reponse' => 'Lazare Defour', 'is_correct' => false],
                    ['reponse' => 'Joan Alburni', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quel athlète du FAC, né le 18 mars, est double détenteur Record club toutes catégories en sprint ?',
                'coach_hint' => 'Ce sprinter porte un prénom de prénom commun',
                'answers' => [
                    ['reponse' => 'Angel Chelala', 'is_correct' => false],
                    ['reponse' => 'Maxence Magat', 'is_correct' => false],
                    ['reponse' => 'Tom Chaussende', 'is_correct' => true],
                    ['reponse' => 'Baptiste Garnier-Triomphe', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quelle sprinteuse du FAC, née le 11 mars, est triple détentrice record Club Espoir ?',
                'coach_hint' => 'Son prénom commence par la lettre M',
                'answers' => [
                    ['reponse' => 'Aalyiah Ebondo', 'is_correct' => false],
                    ['reponse' => 'Justine Garcia', 'is_correct' => false],
                    ['reponse' => 'Maelle Pailleux', 'is_correct' => true],
                    ['reponse' => 'Marine Drapier', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quelle athlète du FAC, née le 11 mars, détient le record club du 50m haies ?',
                'coach_hint' => 'Son prénom est Alexia',
                'answers' => [
                    ['reponse' => 'Justine Garcia', 'is_correct' => false],
                    ['reponse' => 'Alexia Villard', 'is_correct' => true],
                    ['reponse' => 'Camille Bothua', 'is_correct' => false],
                    ['reponse' => 'Cassandre Russier', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quel athlète du FAC, né le 25 avril, s\'est qualifié pour les Championnats de France 2025 au 400m haies ?',
                'coach_hint' => 'Il s\'est qualifié à Saint-Étienne',
                'answers' => [
                    ['reponse' => 'Aymeric Dutrevis', 'is_correct' => false],
                    ['reponse' => 'Joan Alburni', 'is_correct' => true],
                    ['reponse' => 'Joshua Defour', 'is_correct' => false],
                    ['reponse' => 'Samuel Legat', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quel lanceur de poids du FAC, né le 8 mai, est médaillé d\'Argent au Championnat de France ?',
                'coach_hint' => 'Il a obtenu sa médaille en salle à Miramas en 2025 (catégorie Masters)',
                'answers' => [
                    ['reponse' => 'Timeo Pion', 'is_correct' => false],
                    ['reponse' => 'Nathan Epalle', 'is_correct' => false],
                    ['reponse' => 'Emeric Rivière', 'is_correct' => true],
                    ['reponse' => 'Erwan Porte', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quelle athlète du FAC, née le 1er juin, a fini dans le Top 100 au Championnat de France de cross en 2025 ?',
                'coach_hint' => 'Son prénom est Anouk',
                'answers' => [
                    ['reponse' => 'Anouk Legat', 'is_correct' => true],
                    ['reponse' => 'Charlotte Bombillon', 'is_correct' => false],
                    ['reponse' => 'Evie Lebrun', 'is_correct' => false],
                    ['reponse' => 'Lena Lebrun', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quelle athlète du FAC, née le 30 juin, est détentrice du record Club du 100km ?',
                'coach_hint' => 'Elle court aussi à Millau, une course connue en Aveyron',
                'answers' => [
                    ['reponse' => 'Lena Lebrun', 'is_correct' => false],
                    ['reponse' => 'Carole Souchon', 'is_correct' => true],
                    ['reponse' => 'Charlotte Bombillon', 'is_correct' => false],
                    ['reponse' => 'Marine Drapier', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quel athlète du FAC, né le 23 juin, a terminé dans le Top 25 des Championnats de France de cross 2025 ?',
                'coach_hint' => 'Ce fondeur a un prénom peu courant, 7 lettres',
                'answers' => [
                    ['reponse' => 'Samuel Legat', 'is_correct' => false],
                    ['reponse' => 'Steeve Ripamonti', 'is_correct' => false],
                    ['reponse' => 'Thibaud Nael', 'is_correct' => true],
                    ['reponse' => 'Damien Serre', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quel fondeur du FAC, né le 19 juin, détient le record club au semi-marathon ?',
                'coach_hint' => 'Il a couru à Feurs en mars 2025, sous 1h08',
                'answers' => [
                    ['reponse' => 'Joan Lyczak', 'is_correct' => false],
                    ['reponse' => 'Damien Serre', 'is_correct' => true],
                    ['reponse' => 'Thibaud Nael', 'is_correct' => false],
                    ['reponse' => 'Samuel Legat', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quelle demi-fondeuse du FAC, née le 6 juillet, détient le record Club du 800m ?',
                'coach_hint' => 'Elle a battu ce record aux États-Unis en janvier 2025',
                'answers' => [
                    ['reponse' => 'Evie Lebrun', 'is_correct' => false],
                    ['reponse' => 'Lena Lebrun', 'is_correct' => true],
                    ['reponse' => 'Anouk Legat', 'is_correct' => false],
                    ['reponse' => 'Justine Garcia', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quel athlète du FAC, né le 23 septembre, est double Champion de France 2025 ?',
                'coach_hint' => 'Il a gagné à Liévin ET à Saint-Étienne cette année',
                'answers' => [
                    ['reponse' => 'Aymeric Dutrevis', 'is_correct' => false],
                    ['reponse' => 'Erwan Dutrevis', 'is_correct' => true],
                    ['reponse' => 'Thibaud Nael', 'is_correct' => false],
                    ['reponse' => 'Lazare Defour', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quelle athlète du FAC, née le 23 septembre, est détentrice du record club minimes au 200m haies ?',
                'coach_hint' => 'Elle a couru en 29"93 à Grenoble en juin 2025',
                'answers' => [
                    ['reponse' => 'Cassandre Russier', 'is_correct' => true],
                    ['reponse' => 'Angélique Binet', 'is_correct' => false],
                    ['reponse' => 'Coralie Binet', 'is_correct' => false],
                    ['reponse' => 'Alexia Villard', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quel hurdler du FAC, né le 19 septembre, est détenteur du record club Junior au 400m haies ?',
                'coach_hint' => 'Il a couru en 51"67 à Mâcon en juin 2025',
                'answers' => [
                    ['reponse' => 'Aymeric Dutrevis', 'is_correct' => false],
                    ['reponse' => 'Samuel Legat', 'is_correct' => false],
                    ['reponse' => 'Lazare Defour', 'is_correct' => true],
                    ['reponse' => 'Joan Alburni', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quel sprinter du FAC, né le 24 octobre, est détenteur du record club Junior au 100m ?',
                'coach_hint' => 'Il a couru en 10"91 à Saint-Étienne en juin 2025',
                'answers' => [
                    ['reponse' => 'Tom Chaussende', 'is_correct' => false],
                    ['reponse' => 'Baptiste Garnier-Triomphe', 'is_correct' => false],
                    ['reponse' => 'Jules Philibert-Brun', 'is_correct' => true],
                    ['reponse' => 'Angel Chelala', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quel fondeur du FAC, né le 11 octobre, est double finaliste aux Championnats de France en 2025 ?',
                'coach_hint' => 'Il a été finaliste en cross et au 3000m steeple',
                'answers' => [
                    ['reponse' => 'Samuel Legat', 'is_correct' => true],
                    ['reponse' => 'Thibaud Nael', 'is_correct' => false],
                    ['reponse' => 'Arthur Philibert-Brun', 'is_correct' => false],
                    ['reponse' => 'Damien Serre', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quel athlète du FAC, né le 2 novembre, détient le record club senior en hauteur ?',
                'coach_hint' => 'Il a sauté à Clermont-Ferrand en juin 2025',
                'answers' => [
                    ['reponse' => 'Adrien Delaigue', 'is_correct' => true],
                    ['reponse' => 'Nathan Epalle', 'is_correct' => false],
                    ['reponse' => 'Timeo Pion', 'is_correct' => false],
                    ['reponse' => 'Maxence Fournet-Fayard', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quelle athlète du FAC, née le 6 décembre, détient un record club Junior ?',
                'coach_hint' => 'Elle court le 60m en salle, à Aubière en 2025',
                'answers' => [
                    ['reponse' => 'Aaliyah Ebondo', 'is_correct' => true],
                    ['reponse' => 'Marine Drapier', 'is_correct' => false],
                    ['reponse' => 'Sarah Vende', 'is_correct' => false],
                    ['reponse' => 'Camille Bothua', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quel athlète du FAC, né le 22 décembre, détient le record club Master du 800m ?',
                'coach_hint' => 'Il a couru en 2\'01"27 à Décines-Charpieu en juillet 2025',
                'answers' => [
                    ['reponse' => 'Damien Serre', 'is_correct' => false],
                    ['reponse' => 'Thibaud Nael', 'is_correct' => false],
                    ['reponse' => 'Steve Ripamonti', 'is_correct' => true],
                    ['reponse' => 'Joan Lyczak', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quel athlète est, pour la 1ère fois dans l\'histoire du FAC, monté sur le podium des Pointes d\'Or ?',
                'coach_hint' => 'Ce minime 1ère année a terminé 3ème en France aux Pointes d\'Or 2025',
                'answers' => [
                    ['reponse' => 'Adam Defour', 'is_correct' => true],
                    ['reponse' => 'Alexia Villard', 'is_correct' => false],
                    ['reponse' => 'Antoine Merle', 'is_correct' => false],
                    ['reponse' => 'Timeo Klein', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quels sont les 3 lanceurs du FAC qualifiés aux Championnats de France de lancers 2025 à Salon de Provence ?',
                'coach_hint' => 'C\'est une 1ère historique pour le club dans cette compétition',
                'answers' => [
                    ['reponse' => 'Maxence Fournet-Fayard, Erwan Porte, Antoine Merle', 'is_correct' => false],
                    ['reponse' => 'Erwan Porte, Timéo Pion, Camille Bothua', 'is_correct' => false],
                    ['reponse' => 'Erwan Porte, Timéo Klein, Timéo Pion', 'is_correct' => true],
                    ['reponse' => 'Timéo Klein, Maxence Fournet-Fayard, Nathan Epalle', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Quel athlète du FAC, 1ère année cadet, a terminé 5ème du Championnat de France 2025 ?',
                'coach_hint' => 'Il lance très loin pour son âge — plus de 58 mètres !',
                'answers' => [
                    ['reponse' => 'Timéo Klein au javelot', 'is_correct' => true],
                    ['reponse' => 'Lazare Defour au 400m haies', 'is_correct' => false],
                    ['reponse' => 'Joan Alburni au 400m haies', 'is_correct' => false],
                    ['reponse' => 'Adam Defour au 80m', 'is_correct' => false],
                ],
            ],
        ];

        foreach ($questions as $index => $data) {
            $question = Question::create([
                'question' => $data['question'],
                'coach_hint' => $data['coach_hint'],
                'ordre' => $index + 1,
                'is_active' => true,
            ]);

            foreach ($data['answers'] as $answerData) {
                Answer::create([
                    'question_id' => $question->id,
                    'reponse' => $answerData['reponse'],
                    'is_correct' => $answerData['is_correct'],
                ]);
            }
        }

        // Options vote maillot avec les vraies images
        JerseyOption::create([
            'nom' => 'Maillot Option 1',
            'description' => 'Premier design proposé pour le nouveau maillot du FAC',
            'couleurs' => 'Voir image',
            'image_path' => 'img/maillot1.png',
            'is_active' => true,
        ]);
        JerseyOption::create([
            'nom' => 'Maillot Option 2',
            'description' => 'Deuxième design proposé pour le nouveau maillot du FAC',
            'couleurs' => 'Voir image',
            'image_path' => 'img/maillot2.png',
            'is_active' => true,
        ]);
    }
}
