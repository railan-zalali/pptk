<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Performance Target') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.performance-targets.update', $performanceTarget) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Garden Selection -->
                            <div>
                                <label for="kebun_id" class="block text-sm font-medium text-gray-700">Kebun
                                    (Garden)</label>
                                <select name="kebun_id" id="kebun_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required>
                                    <option value="">-- Select Garden --</option>
                                    @foreach ($gardens as $garden)
                                        <option value="{{ $garden->id }}"
                                            {{ old('kebun_id', $performanceTarget->kebun_id) == $garden->id ? 'selected' : '' }}>
                                            {{ $garden->kebun_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Year -->
                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                                <input type="number" name="year" id="year"
                                    value="{{ old('year', $performanceTarget->year) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required min="2000" max="2099">
                            </div>

                            <!-- Target Protas Min -->
                            <div>
                                <label for="target_protas_min" class="block text-sm font-medium text-gray-700">Target
                                    Protas Min (Kg/Ha)</label>
                                <input type="number" step="0.01" name="target_protas_min" id="target_protas_min"
                                    value="{{ old('target_protas_min', $performanceTarget->target_protas_min) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required>
                            </div>

                            <!-- Target Protas Max -->
                            <div>
                                <label for="target_protas_max" class="block text-sm font-medium text-gray-700">Target
                                    Protas Max (Kg/Ha)</label>
                                <input type="number" step="0.01" name="target_protas_max" id="target_protas_max"
                                    value="{{ old('target_protas_max', $performanceTarget->target_protas_max) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required>
                            </div>

                            <!-- Note -->
                            <div class="md:col-span-2">
                                <label for="note" class="block text-sm font-medium text-gray-700">Note</label>
                                <textarea name="note" id="note" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('note', $performanceTarget->note) }}</textarea>
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('admin.performance-targets.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                                Update Target
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
