<?php

namespace App\Livewire\AdminStaff;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Booking;
use App\Models\AssetDetail;
use App\Models\Driver;
use App\Models\VehicleDriverAssignment;

class BookingManagement extends Component
{
    use WithPagination;

    public $selectedBooking = null;
    public $vehicles = [];
    public $drivers = [];

    public $form = [
        'asset_detail_id' => '',
        'brand' => '',
        'model' => '',
        'number_of_seats' => '',
        'plate_number' => '',
        'color' => '',
        'driver_id' => '',
        'odometer_start' => '',
        'odometer_end' => '',
    ];

    protected $listeners = ['viewBookingDetails'];

    public function mount()
    {
        $this->vehicles = AssetDetail::where('asset_type_id', 2)->get();
        $this->drivers = Driver::where('is_active', true)->get();
    }

    public function viewBookingDetails($bookingId)
    {
        $this->resetForm();

        $this->selectedBooking = Booking::with([
            'user.department',
            'user.branch',
            'assetType',
            'assetDetail',
            'vehicleAssignment.driver',
            'vehicleAssignment.assetDetail',
            'bookedGuests',
        ])->findOrFail($bookingId);

        $this->dispatch('open-details-modal');
    }

    public function updatedFormAssetDetailId($value)
    {
        $vehicle = AssetDetail::find($value);
        if ($vehicle) {
            $this->form['brand'] = $vehicle->brand;
            $this->form['model'] = $vehicle->model;
            $this->form['number_of_seats'] = $vehicle->number_of_seats;
            $this->form['plate_number'] = $vehicle->plate_number;
            $this->form['color'] = $vehicle->color;
        } else {
            $this->resetVehicleFields();
        }
    }


    public function allocateVehicle()
    {
        // Validate basic input — skip odometer_end > odometer_start to allow checking duplicates first
        $validated = $this->validate([
            'form.asset_detail_id' => 'required|exists:asset_details,id',
            'form.driver_id' => 'required|exists:drivers,id',
            'form.odometer_start' => 'required|numeric',
            'form.odometer_end' => 'required|numeric',
        ]);

        try {
            // Check if this booking already has this vehicle + driver assigned
            $existing = VehicleDriverAssignment::where('booking_id', $this->selectedBooking->id)
                ->where('asset_detail_id', $this->form['asset_detail_id'])
                ->where('driver_id', $this->form['driver_id'])
                ->first();

            if ($existing) {
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => '✅ Done submitting — already assigned this vehicle and driver.',
                ]);
                $this->dispatch('close-allocate-modal');
                return;
            }

            // Validate odometer_end only if it's not a duplicate
            if ($this->form['odometer_end'] < $this->form['odometer_start']) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Odometer end must be greater than or equal to odometer start.',
                ]);
                return;
            }

            // Proceed with saving
            VehicleDriverAssignment::create([
                'booking_id' => $this->selectedBooking->id,
                'asset_detail_id' => $this->form['asset_detail_id'],
                'driver_id' => $this->form['driver_id'],
                'assigned_by' => auth()->id(),
                'assigned_date' => now(),
                'odometer_start' => $this->form['odometer_start'],
                'odometer_end' => $this->form['odometer_end'],
            ]);

            $this->selectedBooking = $this->selectedBooking->fresh([
                'vehicleAssignment.driver',
                'vehicleAssignment.assetDetail',
            ]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => '✅ Vehicle allocated successfully!',
            ]);

            $this->dispatch('close-allocate-modal');
            $this->resetForm();

        } catch (\Exception $ex) {
            \Log::error('Allocation error', [
                'message' => $ex->getMessage(),
                'booking_id' => $this->selectedBooking->id ?? null,
                'form' => $this->form,
            ]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Something went wrong during vehicle allocation.',
            ]);
        }
    }


    private function resetVehicleFields()
    {
        $this->form['brand'] = '';
        $this->form['model'] = '';
        $this->form['number_of_seats'] = '';
        $this->form['plate_number'] = '';
        $this->form['color'] = '';
    }

    private function resetForm()
    {
        $this->form = [
            'asset_detail_id' => '',
            'brand' => '',
            'model' => '',
            'number_of_seats' => '',
            'plate_number' => '',
            'color' => '',
            'driver_id' => '',
            'odometer_start' => '',
            'odometer_end' => '',
        ];
    }

    public function render()
    {
        $query = Booking::with(['user.department', 'user.branch', 'assetType'])
            ->where('asset_type_id', 2)
            ->latest();

        if (request()->has('status') && in_array(request('status'), ['pending', 'approved', 'rejected'])) {
            $query->where('status', request('status'));
        }

        return view('livewire.admin-staff.booking-management', [
            'bookings' => $query->paginate(10),
        ])->layout('layouts.adminstaff');
    }
}
