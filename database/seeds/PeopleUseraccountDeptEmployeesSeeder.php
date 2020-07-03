<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PeopleUseraccountDeptEmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Set-up an array of Persons */
        $people = [
            [
                'id_number'     => '000195',
                'last_name'     => 'Backian',
                'first_name'    => 'Ian Jones',
                'middle_name'   => 'Bulasao',
                'ext_name'      => 'Jr',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'id_number'     => '000337',
                'last_name'     => 'Arce',
                'first_name'    => 'Jess Emmanuel',
                'middle_name'   => 'Nusog',
                'ext_name'      => '',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'id_number'     => '000095',
                'last_name'     => 'Madongit',
                'first_name'    => 'John',
                'middle_name'   => 'Palog',
                'ext_name'      => 'Jr',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'id_number'     => '000185',
                'last_name'     => 'Flores',
                'first_name'    => 'Bryan',
                'middle_name'   => 'Vigilia',
                'ext_name'      => '',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'id_number'     => '000421',
                'last_name'     => 'Fianza',
                'first_name'    => 'Marielle Angela',
                'middle_name'   => 'P',
                'ext_name'      => '',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
             [
                'id_number'     => '000192',
                'last_name'     => 'Leon',
                'first_name'    => 'Delia',
                'middle_name'   => 'Way-et',
                'ext_name'      => '',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'id_number'     => '000075',
                'last_name'     => 'Lao-e',
                'first_name'    => 'Jovy',
                'middle_name'   => 'Peligman',
                'ext_name'      => '',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'id_number'     => '000051',
                'last_name'     => 'Depayso',
                'first_name'    => 'Dempsey',
                'middle_name'   => '',
                'ext_name'      => '',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]
        ];
        /* Create an array of ID to collect after inserting the People array */
        $ids = [];
        foreach($people as $pip){
            $ids[] = DB::table('people')->insertGetId($pip);
        }

        /* Array of User Account */
        $user_account = [
            /* backian 0*/
            [
                'username'          => '000195',
                'password'          => bcrypt('000195'),
                'user_type'         => 'Instructor',
                'ua_status'            => 1,
                'people_id'         => $ids[0],
                'remember_token'    => '',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            /* arce 1*/
            [
                'username'          => '000337',
                'password'          => bcrypt('000337'),
                'user_type'         => 'Instructor',
                'ua_status'            => 1,
                'people_id'         => $ids[1],
                'remember_token'    => '',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            /* madongit 2 */
            [
                'username'          => '000095',
                'password'          => bcrypt('000095'),
                'user_type'         => 'Instructor',
                'ua_status'            => 1,
                'people_id'         => $ids[2],
                'remember_token'    => '',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            /* flores 3 */
            [
                'username'          => '000185',
                'password'          => bcrypt('000185'),
                'user_type'         => 'Insructor',
                'ua_status'            => 1,
                'people_id'         => $ids[3],
                'remember_token'    => '',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            /* fianza 4 */
            [
                'username'          => '000421',
                'password'          => bcrypt('000421'),
                'user_type'         => 'Insructor',
                'ua_status'            => 1,
                'people_id'         => $ids[4],
                'remember_token'    => '',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            /* leon 5 */
            [
                'username'          => '000192D',
                'password'          => bcrypt('000192D'),
                'user_type'         => 'Dean',
                'ua_status'            => 1,
                'people_id'         => $ids[5],
                'remember_token'    => '',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            /* lao-e 6 */
            [
                'username'          => '000075',
                'password'          => bcrypt('000075'),
                'user_type'         => 'Insructor',
                'ua_status'            => 1,
                'people_id'         => $ids[6],
                'remember_token'    => '',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            /* leon 5 */
            [
                'username'          => '000192A',
                'password'          => bcrypt('000192A'),
                'user_type'         => 'Admin',
                'ua_status'            => 1,
                'people_id'         => $ids[5],
                'remember_token'    => '',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            /* depayso 7 */
            [
                'username'          => '000051D',
                'password'          => bcrypt('000051D'),
                'user_type'         => 'Dean',
                'ua_status'            => 1,
                'people_id'         => $ids[7],
                'remember_token'    => '',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]
        ];

        /* Insert array of user account data */
        DB::table('user_accounts')->insert($user_account);
        /* Insert students table */
        /* DB::table('students')->insert([
            'year_level'    => '4',
            'people_id'     => $ids[4],
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]); */

        
        /* Array of Department table */
        $dept = [
            [
                'dept_code'         => 'CBM',
                'dept_description'  => 'College of Business Management',
                'dept_status'            => 1,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            [
                'dept_code'         => 'COA',
                'dept_description'  => 'College of Accountancy',
                'dept_status'            => 1,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            [
                'dept_code'         => 'CCJE',
                'dept_description'  => 'College of Criminal Justice Education',
                'dept_status'            => 1,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            [
                'dept_code'         => 'CICS',
                'dept_description'  => 'College of Information and Computing Sciences',
                'dept_status'            => 1,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            [
                'dept_code'         => 'CTE',
                'dept_description'  => 'College of Teacher Education',
                'dept_status'            => 1,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            [
                'dept_code'         => 'COT',
                'dept_description'  => 'College of Theology',
                'dept_status'            => 1,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            [
                'dept_code'         => 'TTEd',
                'dept_description'  => 'Trade Technical Education',
                'dept_status'            => 1,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
        ];
        /* Insert array of departments */
        $dpetId = [];
        foreach($dept as $dp){
            $dpetId[] = DB::table('departments')->insertGetId($dp);
        }
        
        /* EMPLOYEES */
        $employees = [
            /* backian */
            [
                'dept_id'    => $dpetId[3],
                'people_id'     => $ids[0],
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            /* jess arce */
            [
                'dept_id'    => $dpetId[3],
                'people_id'     => $ids[1],
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            /* john */
            [
                'dept_id'    => $dpetId[3],
                'people_id'     => $ids[2],
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            /* flores */
            [
                'dept_id'    => $dpetId[3],
                'people_id'     => $ids[3],
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            /* fianza */
            [
                'dept_id'    => $dpetId[3],
                'people_id'     => $ids[4],
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            /* leon */
            [
                'dept_id'    => $dpetId[3],
                'people_id'     => $ids[5],
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            /* leolao-e */
            [
                'dept_id'    => $dpetId[3],
                'people_id'     => $ids[6],
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            /* depayso */
            [
                'dept_id'    => $dpetId[2],
                'people_id'     => $ids[7],
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
        ];
        
        /* Insert Employees Table */
        foreach($employees as $emp){
            DB::table('employees')->insert($emp);
        }
    }
}
