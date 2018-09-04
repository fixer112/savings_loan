<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([
            'name' => 'HoneyPay Admin 1',
            //'email' => 'abula@gmail.com',
            'password' => bcrypt('honeypays2011'),
            'role' => 'admin',
            'mentor' => '01',
            'username' => '0359791700',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
             ]);

       DB::table('users')->insert([
            'name' => 'HoneyPay Staff 1',
            //'email' => 'abuk@gmail.com',
            'password' => bcrypt('abula'),
            'role' => 'staff',
            'username' => '12345',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
             ]);

        DB::table('users')->insert([
            'name' => 'Honeypay Customer 1',
            //'email' => 'abusk@gmail.com',
            'password' => bcrypt('abula'),
            'role' => 'admin2',
            'savings_balance' => '0',
            'username' => '123456',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
             ]);

        DB::table('users')->insert([
            'name' => 'Honeypay Customer 2',
            //'email' => 'abusk@gmail.com',
            'password' => bcrypt('abula'),
            'role' => 'customer',
            'savings_balance' => '0',
            'username' => '1234567',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
             ]);

      /*   DB::table('loans')->insert([
            'due_date' => Carbon::createFromTimeStamp(strtotime('2018/04/05'))->format('Y-m-d H:i:s'),
            'veri_remark' => 'Not Approved',
            'loan_category' => '30,000',
            'staff_id' => '1',
            'user_id' => '3',
            'created_at' => Carbon::createFromTimeStamp(strtotime('2018/03/07'))->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::createFromTimeStamp(strtotime('2018/03/07'))->format('Y-m-d H:i:s'),
             ]);

        DB::table('loans')->insert([
            'due_date' => Carbon::createFromTimeStamp(strtotime('2019/06/07'))->format('Y-m-d H:i:s'),
            'veri_remark' => 'Approved',
            'loan_category' => '60,000',
            'staff_id' => '1',
            'user_id' => '3',
            'created_at' => Carbon::createFromTimeStamp(strtotime('2018/03/08'))->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::createFromTimeStamp(strtotime('2018/03/15'))->format('Y-m-d H:i:s'),
             ]);

        DB::table('historys')->insert([
            'recieved_by' => 'abubakar',
            'description' => 'deposited by abu of 100',
            'debit' => '0',
            'credit' => '100',
            'approved' => 'yes',
            'type' => 'deposit',
            'staff_id' => '1',
            'user_id' => '3',
            'created_at' => Carbon::createFromTimeStamp(strtotime('2018/03/08'))->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::createFromTimeStamp(strtotime('2018/03/15'))->format('Y-m-d H:i:s'),
             ]);

        DB::table('historys')->insert([
            'recieved_by' => 'gbadamosi',
            'description' => 'cash withdrwal of 100',
            'debit' => '100',
            'credit' => '0',
            'approved' => 'yes',
            'type' => 'withdraw',
            'staff_id' => '1',
            'user_id' => '3',
            'created_at' => Carbon::createFromTimeStamp(strtotime('2018/03/08'))->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::createFromTimeStamp(strtotime('2018/03/16'))->format('Y-m-d H:i:s'),
             ]);

        DB::table('historys')->insert([
            'recieved_by' => 'gbadamosi',
            'description' => 'cash withdrwal of 100',
            'debit' => '100',
            'credit' => '0',
            'approved' => 'no',
            'type' => 'loan',
            'staff_id' => '1',
            'user_id' => '3',
            'created_at' => Carbon::createFromTimeStamp(strtotime('2018/03/08'))->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::createFromTimeStamp(strtotime('2018/03/13'))->format('Y-m-d H:i:s'),
             ]);

        DB::table('historys')->insert([
            'recieved_by' => 'gbadamosi',
            'description' => 'cash withdrwal of 100',
            'debit' => '100',
            'credit' => '0',
            'approved' => 'yes',
            'type' => 'loan',
            'staff_id' => '1',
            'user_id' => '3',
            'created_at' => Carbon::createFromTimeStamp(strtotime('2018/03/08'))->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::createFromTimeStamp(strtotime('2018/03/14'))->format('Y-m-d H:i:s'),
             ]);*/
    }
}
