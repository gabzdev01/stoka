<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('articles')->truncate();
        DB::table('testimonials')->truncate();

        $articles = [
            [
                'slug'       => 'the-notebook',
                'title'      => "Your notebook isn't lying to you. It just can't tell you the truth.",
                'preview'    => "A notebook records what gets written in it. The gap between what moved and what was recorded isn't in the notebook.",
                'body'       => "You have been using it for years. It sits next to the till, filled with the handwriting of everyone who has ever worked a shift in your shop.\n\nBut here is what it cannot do. It cannot tell you what the float should be before the shift starts. It cannot compare what was expected with what was counted. It cannot send you a summary at 7pm when you are not in the shop.\n\nThe notebook records what someone chose to write down. Nothing more. If something was not written, it does not exist. If something was written wrong, it looks right.\n\nThis is not a problem with honesty. The problem is that the notebook makes accuracy optional. There is no check. There is no confirmation.\n\nYou count the float and it does not match. You do the subtraction again. The answer is the same. You have no way of knowing what happened because the only record is the one that does not add up.\n\nA system that cannot explain the difference is not protecting you. It is just recording.\n\nStoka replaces the notebook with something that ties every sale to a shift, every shift to a person, and every closing count to an expected number. The moment a shift closes, you can see the difference from wherever you are. Immediately. Without calling anyone.\n\nThe notebook was never the problem. The problem is that it was never designed for accountability. It was designed for memory. Memory is not enough.",
                'sort_order' => 1,
                'published'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug'       => 'last-tuesday',
                'title'      => 'How much did your shop make last Thursday?',
                'preview'    => 'The answer is probably in your head as a feeling. The actual number is somewhere you cannot easily reach.',
                'body'       => "Not roughly. Not what you remember. The actual number — every sale, every payment method, every shilling that moved through the till. Can you produce it right now?\n\nMost boutique owners cannot. They have a feeling. But the actual number — verified, broken down, sitting there waiting to be read — does not exist.\n\nIf you cannot tell what you made last Thursday, you cannot tell whether this Thursday is better or worse. You are running a business on instinct and memory, and they are not the same as information.\n\nThe problem is not laziness. It is structure. A notebook does not produce a daily summary. You would have to do all of that yourself, every day, which is work most people cannot sustain.\n\nSo the information disappears. And you run on instinct.\n\nStoka produces a shift summary automatically when each shift closes. Date, staff name, total sales, M-Pesa, cash, expected float, actual count, discrepancy. You do not have to calculate it. It is there.\n\nLast Thursday is there. Every day is there, in the same format, comparable, readable.\n\nThat is what it means to actually know what you made. Not a feeling. A number.",
                'sort_order' => 2,
                'published'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug'       => 'buying-on-instinct',
                'title'      => 'The real cost of buying on instinct.',
                'preview'    => 'Every restocking trip made without current stock data produces some version of the same outcome. Wrong quantities, wrong sizes, capital sitting instead of moving.',
                'body'       => "You go to the supplier and you buy what feels right. The sizes that seemed to sell. The colours that customers asked about. This is how most boutique owners buy. It is also how most end up with a rack of things that do not move.\n\nThe feeling is based on real experience. But experience stored in memory compresses over time. You remember the things that surprised you. You forget the slow movers that sat for six months.\n\nThe accurate picture is in the sales data. How many of this item sold in the last 90 days. Which sizes moved and which did not. Which items have not sold a single unit since you bought them.\n\nIf you do not have that picture, you buy on instinct. And instinct costs money — tied up in stock that does not move.\n\nThere is a second cost that is harder to see. When you run out of the things that actually sell, customers leave and buy from someone else. The cost of being out of stock on the right things is as real as the cost of being overstocked on the wrong ones.\n\nStoka tracks every sale at the product level. You get a shopping list based on what is actually low. You walk into the supplier with a list instead of a feeling.\n\nThat is the difference between buying well and buying hopefully.",
                'sort_order' => 3,
                'published'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug'       => 'the-staff-problem',
                'title'      => 'The staff problem that is not a staff problem.',
                'preview'    => 'Good people in systems that make honesty hard to prove behave like the system allows. The way to change the outcome is not to change the people.',
                'body'       => "Good people in broken systems behave like the system allows. The way to change behaviour is not to change the people.\n\nMost boutique owners have had a staff problem at some point. Money that did not add up. Stock that seemed to disappear. The natural response is to suspect the person. To watch more carefully. To feel the slow erosion of trust.\n\nBut consider what the system actually offers them. A notebook that can be written in and corrected. A till that has no confirmation step. A float counted at the end of the day by the same person who managed it.\n\nIn that environment, a small dishonesty is invisible. Not because the person is invisible, but because the system makes the act invisible. There is no trail. There is no comparison.\n\nChange the system and you change what is possible. Not by accusing anyone. Not by adding cameras. By making the numbers automatic and transparent.\n\nWhen staff know that every shift produces a reconciliation that goes to the owner — that the expected cash is calculated before they count, not after — behaviour changes. The record is not something they produce. It is something that happens around them.\n\nThis is not punishment. It is clarity. Staff who are honest appreciate it because it protects them.\n\nThe staff problem is usually a system problem. Fix the system first. See what remains.",
                'sort_order' => 4,
                'published'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug'       => 'end-of-day-summary',
                'title'      => 'What your end-of-day summary is not telling you.',
                'preview'    => 'A verbal summary tells you what your staff member remembers happening. It cannot tell you what the system has no mechanism to capture.',
                'body'       => "At the end of the day, you have a number. Maybe written down, maybe in your head. But roughly is doing a lot of work in that sentence.\n\nWhat you do not have is: the exact cash that should be in the till; how that compares to what is actually there; whether returns were processed; what the running credit balance is.\n\nAll of that happened today. None of it is in the summary you have.\n\nThis is not a small gap. Each missing piece is a place where money can move without accountability. Quietly, in the places where the record does not reach.\n\nThe credit book is a good example. Someone buys on credit. Later, they pay. Maybe that payment gets recorded. Maybe it does not. Maybe the notebook gets wet. The debt was real. The record was fragile.\n\nA proper end-of-day summary is not a number. It is a reconciliation. It tells you what came in, how it came in, what should be in the till, what is in the till, and what the difference is. It is signed against a name. It exists permanently.\n\nThat is what Stoka produces at the close of every shift. Not an estimate. A record.",
                'sort_order' => 5,
                'published'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($articles as $article) {
            DB::table('articles')->insert($article);
        }

        DB::table('testimonials')->insert([
            'name'       => 'Wanjiku M.',
            'location'   => 'Zawadi Boutique · Nairobi',
            'pull_quote' => "I used to call James at 7pm to find out how the day went. Now I read it on my phone before he's finished closing.",
            'body'       => "I have two staff members and I'm rarely in the shop myself. Before Stoka, I knew roughly how each week went — roughly. Now I see every shift within minutes of it closing. The discrepancy report was uncomfortable the first time I saw it. It was also the first honest picture I had ever had of what was actually happening in my shop.",
            'published'  => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info(DB::table('articles')->count() . ' articles and ' . DB::table('testimonials')->count() . ' testimonial seeded.');
    }
}
