@extends('fsp.layouts.app')
@section('panel')
    <div class="row">
        <div class="card mb-3 d-flex">
            <div class="card-body">
                <div class="col-lg-12">
                    <h6 class="mb-1">Loans Collected: {{ $fullname->loans_collected }}</h6>
                    <h6 class="mb-1">Loans Repaid: {{ $fullname->loans_repaid }}</h6>
                    <?php 
                        $loan_ratio = "";
                        if($fullname->loans_collected <= 0){
                            $loan_ratio = 0;
                        }else{
                            $loan_ratio = round(($fullname->loans_repaid/$fullname->loans_collected)*100, 2);
                        }
                    ?>
                    <h6>
                        Loan Status: 
                        
                        <span class="mr-2">{{ $loan_ratio }} %</span>
                        
                        @if($loan_ratio == 0)
                            <i class="fas fa-exclamation-circle text-primary"></i> (No Rating Yet!)
                        @elseif($loan_ratio < 50)
                            <i class="fas fa-times text-danger"></i> (Poor Rating)
                        @elseif($loan_ratio >= 50 && $loan_ratio < 100)
                            <i class="fas fa-exclamation-triangle text-warning"></i> (Fair Rating)
                        @elseif($loan_ratio == 100)
                            <i class="fas fa-check text-success"></i> (Good Rating)
                        @endif
                    </h6>
                    
                </div>
            </div>
            
        </div>
        
        <div class="col-lg-12">
            <div class="card b-radius--10 ">

                <div class="card-body p-0">

                    <div class="table-responsive--md  table-responsive">
                            <table class="table table--light style--two">
                                <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Savings Plan')</th>
                                    <th>@lang('Target Amount')</th>
                                    <th>@lang('Total Saved')</th>
                                    <th>@lang('Length Of Savings Interval')</th> 
                                    <th>@lang('Total Times Saved')</th>
                                    <th>@lang('Date Started')</th>
                                    <th>@lang('Date Ended')</th>
                                    <th>@lang('Savings Log')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($savings as $index => $row)
                                <tr>
                                    <td data-label="@lang('S.N')">{{ $index + 1 }}</td>
                                    <td data-label="@lang('Savings Plan')"><a href="{{ url('fsp/loan/detailed/savings/history/'.$row->user_id .'/'. $row->id .'/'. $row->savings_plan_id) }}">{{ ucwords($row->name) }}</a></td>
                                    <td data-label="@lang('Target Amount')">{{ $row->savings_amount }}</td>
                                    <td data-label="@lang('Total Saved')">{{ $row->total_paid }}</td>
                                    <td data-label="@lang('Length Of Savings Interval')">{{ $row->total_installment }}</td>
                                    <td data-label="@lang('Total Times Saved')">{{ $row->installment_given }}</td>
                                    <td data-label="@lang('Date Started')">{{ $row->savings_start }}</td>
                                    <td data-label="@lang('Date Ended')">{{ $row->savings_end }}</td>
                                    <td data-label="@lang('Savings Log')"><i class="fas fa-eye"></i></td>
                                    
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
