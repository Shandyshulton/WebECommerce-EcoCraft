@php($voucher = $voucher ?? null)
@php($claimableDefault = $voucher ? $voucher->is_claimable : true)
@php($activeDefault = $voucher ? $voucher->is_active : true)
@php($rewardDefault = $voucher ? $voucher->is_reward : false)

<div class="mb-2">
    <label class="form-label" style="font-size:11px;font-weight:800">Kode voucher</label>
    <input class="form-control" name="code" value="{{ old('code', $voucher?->code) }}" required placeholder="WELCOME10">
</div>
<div class="mb-2">
    <label class="form-label" style="font-size:11px;font-weight:800">Judul</label>
    <input class="form-control" name="title" value="{{ old('title', $voucher?->title) }}" required placeholder="Diskon Selamat Datang">
</div>
<div class="mb-2">
    <label class="form-label" style="font-size:11px;font-weight:800">Deskripsi</label>
    <textarea class="form-control" name="description" rows="2">{{ old('description', $voucher?->description) }}</textarea>
</div>
<div class="row g-2 mb-2">
    <div class="col-6">
        <label class="form-label" style="font-size:11px;font-weight:800">Tipe</label>
        <select class="form-select" name="type">
            <option value="fixed" @selected(old('type', $voucher?->type ?? 'fixed') === 'fixed')>Nominal (Rp)</option>
            <option value="percent" @selected(old('type', $voucher?->type ?? 'fixed') === 'percent')>Persen (%)</option>
        </select>
    </div>
    <div class="col-6">
        <label class="form-label" style="font-size:11px;font-weight:800">Nilai</label>
        <input class="form-control" type="number" step="0.01" min="0" name="value" value="{{ old('value', $voucher?->value ?? 0) }}" required>
    </div>
</div>
<div class="row g-2 mb-2">
    <div class="col-6">
        <label class="form-label" style="font-size:11px;font-weight:800">Min. belanja (Rp)</label>
        <input class="form-control" type="number" step="0.01" min="0" name="min_spend" value="{{ old('min_spend', $voucher?->min_spend ?? 0) }}">
    </div>
    <div class="col-6">
        <label class="form-label" style="font-size:11px;font-weight:800">Maks. potongan (Rp)</label>
        <input class="form-control" type="number" step="0.01" min="0" name="max_discount" value="{{ old('max_discount', $voucher?->max_discount) }}" placeholder="Kosongkan = tanpa batas">
    </div>
</div>
<div class="row g-2 mb-2">
    <div class="col-6">
        <label class="form-label" style="font-size:11px;font-weight:800">Mulai berlaku</label>
        <input class="form-control" type="date" name="starts_at" value="{{ old('starts_at', optional($voucher?->starts_at)->format('Y-m-d')) }}">
    </div>
    <div class="col-6">
        <label class="form-label" style="font-size:11px;font-weight:800">Berakhir</label>
        <input class="form-control" type="date" name="expires_at" value="{{ old('expires_at', optional($voucher?->expires_at)->format('Y-m-d')) }}">
    </div>
</div>
<div class="row g-2 mb-2">
    <div class="col-6">
        <label class="form-label" style="font-size:11px;font-weight:800">Kuota total</label>
        <input class="form-control" type="number" min="1" name="usage_limit" value="{{ old('usage_limit', $voucher?->usage_limit) }}" placeholder="Kosongkan = tanpa batas">
    </div>
    <div class="col-6">
        <label class="form-label" style="font-size:11px;font-weight:800">Maks. per customer</label>
        <input class="form-control" type="number" min="1" name="per_customer_limit" value="{{ old('per_customer_limit', $voucher?->per_customer_limit ?? 1) }}" required>
    </div>
</div>
<div class="d-grid gap-1 mb-3" style="font-size:12px">
    <label><input type="checkbox" name="is_claimable" value="1" @checked(old('is_claimable', $claimableDefault))> Bisa diklaim customer dari dompet</label>
    <label><input type="checkbox" name="is_reward" value="1" @checked(old('is_reward', $rewardDefault))> Jadikan template reward (tiap 5 pesanan delivered)</label>
    <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $activeDefault))> Aktif</label>
</div>
