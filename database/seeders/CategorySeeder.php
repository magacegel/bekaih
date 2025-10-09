<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\FormType;
use App\Models\PrintHeader;
use App\Models\ShipType;
use App\Models\ShipTypeCategory;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      Category::truncate();

      $name = [];
      $title = [];
      $description = [];

      $name[] = 'TM-1';
      $title[] = 'REPORT TM-1 FOR RECORDING THE THICKNESS MEASUREMENT OF ALL DECK, ALL BOTTOM, OR ALL SIDE PLATING';
      $description[] = 'Report on THICKNESS MEASUREMENT OF ALL DECK, ALL BOTTOM, OR ALL SIDE PLATING';

      $name[] = 'TM-2(i)';
      $title[] = 'REPORT TM-2(i) FOR RECORDING THE THICKNESS MEASUREMENT OF SHELL AND DECK PLATING (one, two or threr transverse section)';
      $description[] = 'Report on THICKNESS MEASUREMENT OF SHELL AND DECK PLATING (one, two or threr transverse section)';

      $name[] = 'TM-2(ii)';
      $title[] = 'REPORT TM-2(ii) FOR RECORDING THE THICKNESS MEASUREMENT OF SHELL AND DECK PLATING (one, two or threr transverse section)';
      $description[] = 'Report on THICKNESS MEASUREMENT OF SHELL AND DECK PLATING (one, two or threr transverse section)';

      $name[] = 'TM-3';
      $title[] = 'REPORT TM-3 FOR RECORDING THE THICKNESS MEASUREMENT OF LONGITUDINAL MEMBERS (one, two or three transverse sections)';
      $description[] = 'Report on THICKNESS MEASUREMENT OF LONGITUDINAL MEMBERS (one, two or three transverse sections)';

      $name[] = 'TM-4';
      $title[] = 'REPORT TM-4 FOR RECORDING THE THICKNESS MEASUREMENT OF TRANSVERSE STRUCTURAL MEMBERS';
      $description[] = 'Report on THICKNESS MEASUREMENT OF TRANSVERSE STRUCTURAL MEMBERS';

      $name[] = 'TM-5';
      $title[] = 'REPORT TM-5 FOR RECORDING THE THICKNESS MEASUREMENT OF TRANSVERSE BULKHEADS';
      $description[] = 'Report on THICKNESS MEASUREMENT OF TRANSVERSE BULKHEADS';

      $name[] = 'TM-6';
      $title[] = 'REPORT TM-6 FOR RECORDING THE THICKNESS MEASUREMENT OF MISCELLANEOUS STRUCTURAL MEMBERS';
      $description[] = 'Report on THICKNESS MEASUREMENT OF MISCELLANEOUS STRUCTURAL MEMBERS';

      $name[] = 'TM-7';
      $title[] = 'REPORT TM-7 FOR RECORDING THE THICKNESS MEASUREMENT OF CARGO HOLD TRANSVERSE FRAMES';
      $description[] = 'Report on THICKNESS MEASUREMENT OF CARGO HOLD TRANSVERSE FRAMES';

      $name[] = 'TM-7 (BC-S31)';
      $title[] = 'REPORT TM-7 (BC-S31) FOR RECORDING THE THICKNESS MEASUREMENT OF CARGO HOLD TRANSVERSE FRAMES';
      $description[] = 'Report on THICKNESS MEASUREMENT OF CARGO HOLD TRANSVERSE FRAMES';

      $name[] = 'TM-8';
      $title[] = 'REPORT TM-8 FOR RECORDING THICKNESS MEASUREMENT OF DECK AND BOTTOM AREA ASSESSMENTS*';
      $description[] = 'Report on THICKNESS MEASUREMENT OF DECK AND BOTTOM AREA ASSESSMENTS*';

      $insert_data = [];

      for($x=0;$x<count($name);$x++)
      {
        $insert_data[] =         
        [ 'id' => $x+1,
          'name' => $name[$x],
          'title' => $title[$x],
          'description' => $description[$x],
          'abbreviation' => $name[$x],
          'order' => $x+1,
        ];
      }

      Category::insert($insert_data);



      FormType::truncate();

      FormType::insert([
        [ 'id' => 1,
          'name' => 'Strength Deck Plating',
          'form_data_format' => 'one',
          'order' => 1,
          'unit_type' => 'plate',
        ],
      ]);

      FormType::insert([
        [ 'id' => 2,
          'name' => 'Strake Position',
          'form_data_format' => 'two',
          'order' => 2,
          'unit_title' => 'STRAKE TYPE',
          'number_wording' => 'Item No',
          'unit_type' => 'prefix',
          'unit_prefix' => 'Strake ',
          'gauged_p_title' => 'Port',
          'gauged_s_title' => 'Starboard',
          'dim_p_title' => 'Diminution P',
          'dim_s_title' => 'Diminution S',
        ],
      ]); 

      FormType::insert([
        [ 'id' => 3,
          'name' => 'Stringer Plate',
          'form_data_format' => 'three',
          'order' => 3,
          'number_wording' => 'No or Letter',
          'unit_type' => 'free_text',
        ],
      ]);



      ShipType::truncate();
      ShipType::insert([

        [ 'id' => 1,
          'type' => 'Single Hull Oil Tanker',
        ],
        [ 'id' => 2,
          'type' => 'Double Hull Oil Tanker',
        ],
      ]);

      ShipTypeCategory::truncate();
      ShipTypeCategory::insert([

        [ 'id' => 1,
          'ship_type_id' => '1',
          'category_id' => '1',
          'form_type_id' => '1',

        ],
        [ 'id' => 2,
          'ship_type_id' => '1',
          'category_id' => '1',
          'form_type_id' => '2',
        ],
        [ 'id' => 3,
          'ship_type_id' => '1',
          'category_id' => '2',
          'form_type_id' => '3',
        ],
      ]);



    }
}
