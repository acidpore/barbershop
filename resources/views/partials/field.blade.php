@php($type = $type ?? 'text')
<div>
    <label class="block text-sm font-medium text-neutral-700 mb-1">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value ?? '') }}"
           class="w-full rounded-lg border-neutral-300 focus:border-neutral-800 focus:ring-neutral-800">
    @error($name) <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
</div>
