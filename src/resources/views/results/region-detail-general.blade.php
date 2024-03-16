<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __("В разрезе школ") }}</h3>
    </div>
        <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>{{ __("Школа") }}</th>
                    <th>{{ __("Респондентов") }}</th>
                    <th>{{ __("Контингент") }}</th>
                    @if($interprets->count() > 0)
                        @foreach ($interprets as $interpret)
                            

                            <th>{{$interpret->from}}
                                @if ($interpret->to > 500)
                                {{ __("и более") }}
                                @else
                                    -{{$interpret->to}}
                                @endif
                            </th>
                        @endforeach
                    @endif                    
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schools as $school) 
                  <tr>
                    <td>{{$school['school']}}</td>
                    <td>
                        {{$school['count']}} {{ __("человек") }}
                    </td>
                    <td>{{$school['contingent']}}</td>

                    @if($interprets->count() > 0)
                      @foreach($school['ranges'] as $range)
                        <td>{{$range['count']}} {{ __("человек") }}</td>
                      @endforeach
                    @endif

                    <td><a href="/moderator/results/school/quiz/{{$school['school_id']}}/{{$quiz->id}}">{{ __("Подробно") }}</a> </td>
                  </tr>
                @endforeach		
            </tbody>
            <tfoot>
                <tr>
                    
                    <th>{{ __("Итого") }}:</th>
                    <th>{{ __("Всго пройдено") }}:  {{$quiz->respondent_result()->get()->count()}}</th>
                    <th>{{ __("Средний бал") }}: {{round($quiz->respondent_result()->get()->avg('scope'),2)}}</th>
                    @if($interprets->count() > 0)
                      @foreach($by_range_all as $by_range)
                        <th>
                        {{ __("от") }} {{$by_range['range']['from']}} 
                            @if ($by_range['range']['to'] > 500)
                            {{ __("и более") }}:
                            @else
                            {{ __("до") }} {{$by_range['range']['to']}}:
                            @endif
                             {{$by_range['count']}}
                        </th>
                      @endforeach
                    @endif
                    <th></th>

                </tr>
             </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>