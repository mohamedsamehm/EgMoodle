<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('questions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('questions')->insert([
            [
                'title' => 'A Neural Network is an interconnected assembly of simple processing elements based on the animal ...............',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'Different Applications of Artificial Neural Networks include',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'The following Architecture Represents a von-Neumann architecture',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'Which of these are true about neural networks?',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'Which of these are not considered as features of neural networks?',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'In BNN , Soma , Synapse , and axon are equivalent to ....... , ........ , ....... In ANN.',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'The choice of activation function determines the neuron model',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => '........... can be incorporated as another weight clamped to a fixed input.',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'ANN can be classified on the basis of:',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'the layer that receive the external input signals and perform no computation is called ...............',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'the layer receive signals from neurons either in the input layer or in a hidden layer is called ...............',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'the layer that are connected between the input layer and the output layer is called ...............',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'Which of the following is an activation function?',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'categorization of input data into identifiable classes is called pattern recognition',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'Which of the following is an activation function?',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => '.............is defined as: a measurement taken on the input pattern that is to be classified.',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'XOR can’t be modeled by perceptron',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'The type of learning that doesn’t require a correct answer associated with each input pattern is called ..............',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'The type of learning that require a correct answer for every input stream is called ..............',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],[
                'title' => 'An epoch means training the neural network with all the training data set for ten cycles.',
                'type' => 'One Answer',
                'exam_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
