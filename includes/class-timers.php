<?php

namespace AntiqueWP;

defined( 'ABSPATH' ) || exit;

// Class to manage timers
class Timers {

    // Constructor to initialize shortcodes
    public function __construct() {
        // Register the shortcodes
        add_shortcode('timer_main', [ $this, 'timer_main_func' ]);
        add_shortcode('timer_before', [ $this, 'timer_before_func' ]);
        add_shortcode('timer_after', [ $this, 'timer_after_func' ]);
    }

    // Main timer shortcode
    private function timer_main_func() {
        // Get the timer end date from the options
        $end_time = get_option('antique_wp_field_timer_end');
        
        $message  = '<div class="timer">';
        $message .= '<div class="timer__content">';
        $message .= '<h3 class="timer__title">Sales end soon!</h3>';
        $message .= '<div class="timer__block" id="timer-main" data-timer="' . esc_attr($end_time) . '">';	
        $message .= '<span>00</span> : <span>00</span>';	
        $message .= '</div>';	
        $message .= '<div class="timer__titles">';		
        $message .= '<h4>Hours</h4>';		
        $message .= '<h4>Minutes</h4>';		
        $message .= '</div>';	
        $message .= '</div>';	
        $message .= '</div>';			
						
        return $message;
    }

    // Timer before sale shortcode
    private function timer_before_func() {
        // Get the timer start date from the options
        $start_time = get_option('antique_wp_field_timer_start');
        
        $message  = '<div class="timer">';
        $message .= '<div class="timer__content">';
        $message .= '<h3 class="timer__title">The next sale will start!</h3>';
        $message .= '<div class="timer__block" id="timer-before" data-timer="' . esc_attr($start_time) . '">';	
        $message .= '<span>00</span> : <span>00</span>';	
        $message .= '</div>';	
        $message .= '<div class="timer__titles">';		
        $message .= '<h4>Hours</h4>';		
        $message .= '<h4>Minutes</h4>';		
        $message .= '</div>';	
        $message .= '</div>';	
        $message .= '</div>';			
						
        return $message;
    }

    // Timer after sale shortcode
    private function timer_after_func() {
        $message  = '<div class="timer">';
        $message .= '<div class="timer__content">';
        $message .= '<h3 class="timer__title">The sales end!</h3>';
        $message .= '<div class="timer__block" id="timer-after" data-timer="">';	
        $message .= '<span>00</span> : <span>00</span>';	
        $message .= '</div>';	
        $message .= '<div class="timer__titles">';		
        $message .= '<h4>Hours</h4>';		
        $message .= '<h4>Minutes</h4>';		
        $message .= '</div>';	
        $message .= '</div>';	
        $message .= '</div>';			
						
        return $message;
    }
}