SELECT DISTINCT 
total.pn, 
ep.nome, 
ep.cnpj AS epEmpresa_cnpj, 
total.loja_cnpj, 
data, 
pr.ean, 
SUM(venda) AS somaVenda, 
SUM(estoque) AS somaEstoque, 
pr.familia, 
pr.tipo, 
lj.sitio, 
lj.cidade, 
lj.tipo_sitio, 
lj.estado, 
DATE(NOW()) AS data_atual, 
TIME(NOW()) AS hora_atual 

FROM( 
		(SELECT DISTINCT pn,loja_cnpj,empresa_cnpj,data,0 as estoque, quantidade AS venda FROM var_venda WHERE 1 AND data >= '2014-03-05' AND data <= '2014-03-05' AND empresa_cnpj = '11111111111111' GROUP BY pn,loja_cnpj,empresa_cnpj,data) 
	UNION 
		(SELECT DISTINCT pn,loja_cnpj,empresa_cnpj,data,quantidade AS estoque, 0 AS venda FROM var_estoque WHERE 1 AND data >= '2014-03-05' AND data <= '2014-03-05' AND empresa_cnpj = '11111111111111' GROUP BY pn,loja_cnpj,empresa_cnpj,data) ) AS total

INNER JOIN var_empresa AS ep
INNER JOIN var_produto AS pr 
INNER JOIN var_loja AS lj 

WHERE 
lj.cnpj = total.loja_cnpj AND 
data >= '2014-03-05' AND 
data <= '2014-03-05' AND
ep.cnpj = total.empresa_cnpj AND 
pr.pn = total.pn AND
ep.cnpj = '11111111111111'

GROUP BY 
total.loja_cnpj,
total.pn

ORDER BY
pn, 
nome