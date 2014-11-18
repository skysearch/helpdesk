SELECT  DISTINCT 
ep.cnpj AS empresa_cnpj,
lj.cnpj AS loja_cnpj,
pr.pn,
pr.ean,
pr.tipo,
pr.familia,
lj.sitio, 
lj.cidade, 
lj.tipo_sitio, 
lj.estado,
(SELECT SUM(ve.quantidade) AS vendas FROM var_venda AS ve WHERE lj.cnpj = ve.loja_cnpj AND ve.pn = pr.pn GROUP BY pn,data LIMIT 1) AS vendas,
(SELECT SUM(es.quantidade) AS estoque FROM var_estoque AS es WHERE lj.cnpj = es.loja_cnpj AND es.pn = pr.pn GROUP BY pn,data ORDER BY data DESC LIMIT 1) AS estoque

FROM 
var_loja AS lj,
var_produto AS pr,
var_empresa AS ep

WHERE
ep.cnpj = '22222222222222'  AND
lj.empresa_cnpj = ep.cnpj

GROUP BY
pr.pn,
lj.cnpj

HAVING
(vendas IS NOT NULL OR estoque IS NOT NULL)

ORDER BY
empresa_cnpj,
loja_cnpj,
pn
