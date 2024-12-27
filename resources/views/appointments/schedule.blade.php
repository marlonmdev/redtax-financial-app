<form action="#" method="GET">
    <input type="date" name="date" value="{{ $date }}" min="{{ now()->format('Y-m-d') }}">
    <select name="service_id">
        @foreach ($services as $service)
            <option value="{{ $service->id }}">{{ $service->name }}</option>
        @endforeach
    </select>
    <button type="submit">Show Available Timeslots</button>
</form>

<div id="timeslots">
    @forelse ($availableTimeslots as $timeslot)
        <div>{{ $timeslot->start_time }} - {{ $timeslot->end_time }}</div>
    @empty
        <div>No services added yet...</div>
    @endforelse
</div>

<form action="{{ route('appointments.book-appointment') }}" method="POST">
    @csrf
    <input type="hidden" name="date" value="{{ $date }}">
    <input type="hidden" name="service_id" value="{{ $serviceId }}">
    <select name="timeslot_id">
        @foreach ($availableTimeslots as $timeslot)
            <option value="{{ $timeslot->id }}">{{ $timeslot->start_time }} - {{ $timeslot->end_time }}</option>
        @endforeach
    </select>
    <button type="submit">Book Appointment</button>
</form>
