@extends('fsp.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">

                <div class="card-body p-0">

                    <div class="table-responsive--md  table-responsive">
                            <table class="table table--light style--two">
                                <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Savings Plan')</th>
                                    <th>@lang('Amount Saved')</th>
                                    <th>@lang('Date Saved')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($savings as $index => $row)
                                <tr>
                                    <td data-label="@lang('S.N')">{{ $index + 1 }}</td>
                                    <td data-label="@lang('Savings Plan')">{{ ucwords($row->name) }}</a></td>
                                    <td data-label="@lang('Target Amount')">{{ $row->savings_amount }}</td>
                                    <td data-label="@lang('Date Ended')">{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
                                    
                                </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
    
                                </tbody>
                            </table><!-- table end -->
                        </div>

                </div>
                <?php
                /*
                @if ($loanPlans->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($loanPlans) }}
                    </div>
                @endif
                */
                ?>
            </div>
        </div>
    </div>


@endsection
