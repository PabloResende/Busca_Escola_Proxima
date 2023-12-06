O site permite aos usuários calcular a distância entre sua residência e uma escola específica, fornecendo o nome da escola e seu endereço. O processo é realizado da seguinte forma:

1.O usuário fornece o nome da escola e seu próprio endereço.

2.Utilizando o serviço de geocodificação do OpenStreetMap (OSM), o endereço do usuário é convertido em coordenadas geográficas (latitude e longitude).

3.O sistema verifica se as coordenadas do endereço foram obtidas com sucesso.

4.A lista de escolas, contendo nomes e coordenadas geográficas, é consultada para encontrar a escola com o nome fornecido pelo usuário.

5.Se a escola for encontrada, a distância entre a residência do usuário e a escola é calculada usando a fórmula de Haversine.

6.Com base na distância calculada, o sistema exibe uma mensagem indicando se o usuário precisa ou não pegar o ônibus para chegar à escola.

7.Mensagens de erro são exibidas caso haja problemas na obtenção das coordenadas do endereço, se o nome da escola não for fornecido ou se a escola não for encontrada na lista.

Em resumo, o site oferece uma ferramenta simples para ajudar os usuários a determinar se precisam ou não pegar o ônibus para chegar a uma escola específica, com base na distância entre suas casas e a escola desejada.