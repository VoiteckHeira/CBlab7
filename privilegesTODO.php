/*
-- konto
0. rola admina bedzie mogła wyświetlać role i uprawnienia (dodać nowe uprawnienie do tego) i tylko admin bedzie mógł
tworzyć nowe role i uprawnienia
1. funkcja do pobierania z bazy listy utworzonych ról i wyświetlania ich jako lista do zaznaczenia w formularzu
tworzenia konta
2. funkcja do pobierania z bazy uprawnienia przypisanego do konta i wyświetlania go pod nazwą.
3. fukcja do pobierania z bazy listy utworzonych uprawnień i wyświetlania ich przy roli. w zalogowanym koncie


--listy
1. funkcja do pobierania z bazy listy wszystkich ról i wyświetlania ich jako lista
2. funckja do pobierania z bazy listy wszystkich uprawnień i wyświetlania ich jako lista pod uprawnieniami
3.

-- w bazie
== dodać odpowiednie tabele
0. wyczyścic tabele z uprawnieniami i rolami i ponumerować od nowa
1. tabela z uprawnieniami
2. tabela z rolami
3. porobić powiązania
4. dodać uprawnienie dla roli admin o tworzeniu, usuwaniu i edycji uprawnień
5. dodać uprawnienie dla roli admin o tworzeniu, usuwaniu i edycji ról
6. dodać uprawnienie dla roli admin o przypisywaniu uprawnień do ról


-- w formularzu rejestracji
1. dodać pole do wyboru roli
2. dodać automatyczne przypisanie roli i uprawnień do konta
3. domyślnie wybrany jest rola 'new'

-- po zalogowaniu
1. dodać wyświetlanie roli i uprawnień przy nazwie

-- funkcje
1. Dodaj do aplikacji funkcje edycji wiadomości.
2. Dodaj do aplikacji funkcje usuwania wiadomości.
3. Przyciski przy wiadomości ALBO na dole jest wyświelne 2 pola, jedno z usuwaniem, jedno z wyborem do edycji i
wybieramy
3.1 pola są widoczne tylko jeśli masz uprawnienia
4. pobieranie z bazy listy wszystkich ról i wyświetlania ich jako lista
5. pobieranie z bazy listy wszystkich uprawnień i wyświetlania ich jako lista pod uprawnieniami


-- notatki
1. przy tworzeniu roli wymagane jest podanie uprawnień
2. jebane chyba trzeba dodać dodawanie uprawnień poza rolą
2. "Zapisz listę uprawnień w sesji."
4. "Podczas wyświetlania strony pokazuj użytkownikowi tylko te elementy, do których ma uprawnienia."
*/