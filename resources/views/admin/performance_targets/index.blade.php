<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Performance Targets (Target Protas)') }}
            </h2>
            <a href="{{ route('admin.performance-targets.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add Target
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th
                                        class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Year</th>
                                    <th
                                        class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Garden</th>
                                    <th
                                        class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Min Target (Kg/Ha)</th>
                                    <th
                                        class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Max Target (Kg/Ha)</th>
                                    <th
                                        class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Note</th>
                                    <th
                                        class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($targets as $target)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $target->year }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $target->garden->kebun_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ number_format($target->target_protas_min, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ number_format($target->target_protas_max, 2) }}</td>
                                        <td class="px-6 py-4">{{ Str::limit($target->note, 50) }}</td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <a href="{{ route('admin.performance-targets.edit', $target) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                            <form action="{{ route('admin.performance-targets.destroy', $target) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No performance
                                            targets found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $targets->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
