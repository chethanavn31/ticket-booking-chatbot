getLocationIDQuery = """ select location_id from location where name = %s """

getMovieIDQuery = """ select movie_id,posterURL from movie where name = %s """

getTheatreIDQuery = """ select theatre_id,layout from theatre where name = %s """

getShowIDQuery = """ select show_id,fromTime from showtiming where type = %s and theatre_id_sh = %s """

getShowTimeQuery = """ select type from showtiming where theatre_id_sh = %s """

getDateQuery = """ select fromDate,toDate from movie where movie_id = %s """

addUserQuery = """ insert into user (user_id,username,status) values(%s, %s, 'Active') """

searchMovieQuery=""" select distinct mv.movie_id,mv.name,director,genre,posterURL
                     from movie mv,
                     location l,
                     movie_theatre mt,
                     location_theatre lt          
                     where l.name = %s
                     and l.location_id = lt.location_id_l
                     and lt.theatre_id_l = mt.theatre_id_m
                     and mt.movie_id = mv.movie_id
                     and (mv.genre = %s or mv.name = %s) and mv.status = 'Active' """                      
            
getLocationQuery = """ select * from location where status = 'Active' """

getUserQuery = """ select * from user where user_id = %s """

getMovieQuery = """ Select distinct mv.movie_id,mv.name,director,genre,posterURL
                    from movie mv,
                    location l,
                    movie_theatre mt,
                    location_theatre lt
                    where l.name = %s
                    and l.location_id = lt.location_id_l
                    and lt.theatre_id_l = mt.theatre_id_m
                    and mt.movie_id = mv.movie_id
                    and mv.status = 'Active' """

getTheatreQuery = """Select distinct t.theatre_id,t.name
                    from theatre t,movie m, movie_theatre mt,location_theatre lt,location l
                    where m.name = %s and l.location_id = %s
                    and m.movie_id = mt.movie_id
                    and l.location_id = lt.location_id_l
                    and lt.theatre_id_l = t.theatre_id
                    and t.theatre_id = mt.theatre_id_m
                    and t.status='Active'  """
