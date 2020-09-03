-- ================================================
USE [SIGEUNBB]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE bib.Sp_SEL_bienes

AS
BEGIN
	
	SET NOCOUNT ON

	SELECT ROW_NUMBER() OVER(ORDER BY b.iBienId) AS N_,
	   b.iBienId
      ,b.iGrupoBienesId
      ,b.iLocalId
      ,b.cCodPatrimonial
      ,b.cISBN
      ,b.cISSN
      ,b.iTipoBienId
      ,b.iClasificacionMaterialId
      ,b.iClasiMaterialDetId
      ,b.cTitulo
      ,b.cMateriaTema
      ,b.iAutorId
      ,b.cVolumenTomo
      ,b.cIncluye
      ,b.iAnhoPublicacion
      ,b.cNumeroEdicion
      ,b.iAnhoEdicion
      ,b.iEditorialId
      ,b.cCiudad
      ,b.cPais
      ,b.iNumPaginas
      ,b.iEstadoBienId
      ,b.iCarreraId
      ,b.dFechaBaja
      ,b.dFechaIngreso
      ,b.dFechaEgreso
      ,b.cObservaciones
      ,b.cDescripcion_Mat
      ,b.cSerial_Mat
      ,b.cModelo_Mat
      ,b.cMarca_Mat
      ,b.cColor
      ,b.cPortada
      ,b.bHabilitado
	FROM bib.bienes AS b
	
	RETURN 1
END
GO
