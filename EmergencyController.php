<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class EmergencyController extends Controller
{
    public function getEmergencyData($destinationId)
    {
        $destination = Destination::findOrFail($destinationId);
        $name = strtolower($destination->name);

        $hospitals = [];
        $police = [];
        $fuel = [];

        if (str_contains($name, 'hunza')) {
            $hospitals = [
                ['name' => 'Aga Khan Family Health Centre, Aliabad', 'phone' => '+92-581-3455012', 'distance' => '4.2 km'],
                ['name' => 'Civil Hospital, Ganish', 'phone' => '+92-581-3455110', 'distance' => '5.8 km']
            ];
            $police = [
                ['name' => 'Aliabad Police Station', 'phone' => '+92-581-3455024', 'distance' => '3.8 km'],
                ['name' => 'Karimabad Tourist Police Checkpost', 'phone' => '+92-581-3455025', 'distance' => '1.2 km']
            ];
            $fuel = [
                ['name' => 'PSO Fuel Station, Aliabad', 'details' => 'Open 24/7, Petrol & Diesel available', 'distance' => '4.0 km'],
                ['name' => 'Shell Active Filling Station, Ganish', 'details' => 'Open 6 AM - 11 PM', 'distance' => '6.0 km']
            ];
        } elseif (str_contains($name, 'skardu')) {
            $hospitals = [
                ['name' => 'DHQ Hospital Skardu, Hamidgarh', 'phone' => '+92-581-5920204', 'distance' => '2.5 km'],
                ['name' => 'CMH Skardu (Military Hospital)', 'phone' => '+92-581-5453022', 'distance' => '3.1 km']
            ];
            $police = [
                ['name' => 'Cantt Police Station, Skardu', 'phone' => '+92-581-5920333', 'distance' => '1.8 km'],
                ['name' => 'Tourist Rescue & Help Desk, Airport Road', 'phone' => '+92-581-5920334', 'distance' => '5.0 km']
            ];
            $fuel = [
                ['name' => 'PSO City Filling Station, Skardu', 'details' => 'Hi-Octane, Petrol & Diesel', 'distance' => '1.5 km'],
                ['name' => 'Total Parco Station, Shigar Road', 'details' => 'Open 24/7', 'distance' => '4.2 km']
            ];
        } elseif (str_contains($name, 'murree')) {
            $hospitals = [
                ['name' => 'THQ Hospital Murree, Cart Road', 'phone' => '+92-51-9269001', 'distance' => '1.0 km'],
                ['name' => 'Combined Military Hospital (CMH) Murree', 'phone' => '+92-51-9269112', 'distance' => '1.5 km']
            ];
            $police = [
                ['name' => 'Traffic & Emergency Police, Mall Road', 'phone' => '+92-51-9269222', 'distance' => '0.2 km'],
                ['name' => 'Bhurban Police Station', 'phone' => '+92-51-9269223', 'distance' => '8.0 km']
            ];
            $fuel = [
                ['name' => 'Shell Fuel Station, Cart Road', 'details' => '24 hours fuel & tyre shop', 'distance' => '1.1 km'],
                ['name' => 'Caltex Station, Sunny Bank', 'details' => 'Open 24/7', 'distance' => '2.5 km']
            ];
        } elseif (str_contains($name, 'swat')) {
            $hospitals = [
                ['name' => 'Saidu Group of Teaching Hospitals, Mingora', 'phone' => '+92-946-9240121', 'distance' => '12.0 km (from Kalam)'],
                ['name' => 'Kalam Red Crescent Medical Centre', 'phone' => '+92-946-751201', 'distance' => '1.5 km']
            ];
            $police = [
                ['name' => 'Kalam Police Station', 'phone' => '+92-946-751221', 'distance' => '0.9 km'],
                ['name' => 'Mingora City Police Station', 'phone' => '+92-946-9240131', 'distance' => '25.0 km']
            ];
            $fuel = [
                ['name' => 'PSO Kalam Fuel Station', 'details' => 'Last major fuel pump going up to Ushu', 'distance' => '1.0 km'],
                ['name' => 'Total Parco, Bahrain Swat', 'details' => 'Open 24/7', 'distance' => '22.0 km']
            ];
        } else { // Naran Kaghan
            $hospitals = [
                ['name' => 'Civil Hospital Naran, Bypass Road', 'phone' => '+92-997-430112', 'distance' => '1.1 km'],
                ['name' => 'Balakot Rural Health Centre', 'phone' => '+92-997-501021', 'distance' => '38.0 km']
            ];
            $police = [
                ['name' => 'Naran Valley Police Station', 'phone' => '+92-997-430211', 'distance' => '0.5 km'],
                ['name' => 'Kaghan Patrol Checkpost', 'phone' => '+92-997-430212', 'distance' => '18.0 km']
            ];
            $fuel = [
                ['name' => 'PSO Naran Fuel Station', 'details' => 'High pressure diesel and petrol, heavily crowded in summer', 'distance' => '0.8 km'],
                ['name' => 'Shell Fuel Pump, Balakot', 'details' => 'Open 24/7', 'distance' => '38.0 km']
            ];
        }

        return response()->json([
            'destination' => $destination->name,
            'rescue_helpline' => 'Rescue 1122 / Police 15 (Universal Pakistan Helplines)',
            'hospitals' => $hospitals,
            'police' => $police,
            'fuel' => $fuel
        ]);
    }
}
