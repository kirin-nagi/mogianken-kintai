<!-- 勤怠一覧 -->
<!-- @foreach ($attendances as $attendance)
<tr>
    <td>{{ $attendance->start_time->format('H:i') }}</td>
    <td>{{ $attendance->end_time?->format('H:i') ?? '-' }}</td>
    <td>{{ gmdate('H:i', $attendance->total_work) }}</td>
</tr>
@endforeach -->